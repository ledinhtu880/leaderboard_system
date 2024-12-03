import mysql.connector
import numpy as np
from sklearn.preprocessing import StandardScaler
from sklearn.cluster import KMeans
import json
from collections import defaultdict

def connect_database():
    """Kết nối với MySQL database"""
    return mysql.connector.connect(
        host="localhost",
        user="root",
        password="",
        database="cluster"
    )

def fetch_members():
    """Lấy dữ liệu từ bảng member"""
    try:
        conn = connect_database()
        cursor = conn.cursor(dictionary=True)
        cursor.execute("SELECT * FROM members")
        members = cursor.fetchall()
        cursor.close()
        conn.close()
        return members
    except Exception as e:
        print(f"Error fetching data: {str(e)}")
        return []

def encode_categorical(members, feature):
    """Mã hóa dữ liệu categorical thành số"""
    unique_values = list(set(str(m[feature]) for m in members))
    value_to_num = {val: i/len(unique_values) for i, val in enumerate(unique_values)}
    return [value_to_num[str(m[feature])] for m in members]

def prepare_data(members):
    """Chuẩn bị dữ liệu cho clustering với trọng số"""
    try:
        if not members:
            return None
            
        # Chuẩn hóa dữ liệu số
        numeric_features = np.array([
            [float(m['gpa']), float(m['last_gpa'])]
            for m in members
        ])
        scaler = StandardScaler()
        numeric_scaled = scaler.fit_transform(numeric_features)
        
        # Tính điểm tổng hợp với trọng số
        weighted_features = []
        for i in range(len(members)):
            feature = np.array([
                numeric_scaled[i][0] * 0.5,  # GPA (50%)
                numeric_scaled[i][1] * 0.5,  # last_GPA (50%)
            ])
            weighted_features.append(feature)
        
        return np.array(weighted_features)
    except Exception as e:
        print(f"Error preparing data: {str(e)}")
        return None

def distribute_to_groups(members, features, n_groups=4, members_per_group=5):
    """Phân phối thành viên vào các nhóm có kích thước cố định với sự cân bằng về điểm số"""
    try:
        # Tính điểm tổng hợp cho mỗi thành viên
        member_scores = []
        for i, member in enumerate(members):
            score = float(member['gpa']) * 0.5 + \
                    float(member['last_gpa']) * 0.5
            member_scores.append((i, score, member))
        
        # Sắp xếp thành viên theo điểm số
        member_scores.sort(key=lambda x: x[1], reverse=True)
        
        # Khởi tạo các nhóm trống
        final_groups = {i: [] for i in range(n_groups)}
        
        # Phân phối thành viên theo pattern để đảm bảo cân bằng
        # Pattern: Người giỏi nhất vào nhóm 1, giỏi thứ 2 vào nhóm 2,...
        for i in range(len(member_scores)):
            group_id = i % n_groups
            if len(final_groups[group_id]) < members_per_group:
                final_groups[group_id].append(member_scores[i][2])
        
        # Kiểm tra và đảm bảo mỗi nhóm có đúng số lượng thành viên
        for group_id in final_groups:
            if len(final_groups[group_id]) != members_per_group:
                raise Exception(f"Group {group_id} does not have exactly {members_per_group} members")
        
        # Tối ưu hóa bằng cách hoán đổi các thành viên giữa các nhóm
        max_iterations = 100
        iteration = 0
        
        while iteration < max_iterations:
            improved = False
            
            # Tính điểm trung bình của mỗi nhóm
            group_avgs = {i: sum(float(m['gpa']) for m in group) / len(group) 
                         for i, group in final_groups.items()}
            
            # Tìm nhóm có điểm cao nhất và thấp nhất
            max_group = max(group_avgs.items(), key=lambda x: x[1])[0]
            min_group = min(group_avgs.items(), key=lambda x: x[1])[0]
            
            # Nếu chênh lệch quá nhỏ, dừng tối ưu
            if abs(group_avgs[max_group] - group_avgs[min_group]) < 0.1:
                break
            
            # Thử hoán đổi thành viên
            for i in range(members_per_group):
                for j in range(members_per_group):
                    # Tính điểm trung bình sau khi hoán đổi
                    temp_max_group = list(final_groups[max_group])
                    temp_min_group = list(final_groups[min_group])
                    
                    # Hoán đổi thử nghiệm
                    temp_max_group[i], temp_min_group[j] = temp_min_group[j], temp_max_group[i]
                    
                    new_max_avg = sum(float(m['gpa']) for m in temp_max_group) / members_per_group
                    new_min_avg = sum(float(m['gpa']) for m in temp_min_group) / members_per_group
                    
                    # Nếu hoán đổi cải thiện sự cân bằng
                    current_diff = abs(group_avgs[max_group] - group_avgs[min_group])
                    new_diff = abs(new_max_avg - new_min_avg)
                    
                    if new_diff < current_diff:
                        # Thực hiện hoán đổi
                        final_groups[max_group][i], final_groups[min_group][j] = \
                            final_groups[min_group][j], final_groups[max_group][i]
                        improved = True
                        break
                if improved:
                    break
            
            if not improved:
                break
                
            iteration += 1

        # Kiểm tra lại lần cuối
        for group_id, group in final_groups.items():
            if len(group) != members_per_group:
                raise Exception(f"Final group {group_id} does not have exactly {members_per_group} members")
        
        return final_groups
        
    except Exception as e:
        print(f"Error distributing groups: {str(e)}")
        return None

def main():
    """Hàm chính để thực hiện chia nhóm và trả về kết quả cho Laravel"""
    try:
        # Lấy dữ liệu từ database
        members = fetch_members()
        if not members:
            return json.dumps({"error": "No members found"})
        
        # Chuẩn bị dữ liệu với trọng số
        features = prepare_data(members)
        if features is None:
            return json.dumps({"error": "Error preparing data"})
        
        # Thực hiện clustering
        kmeans = KMeans(n_clusters=4, random_state=42, n_init=10)
        clusters = kmeans.fit_predict(features)
        
        # Tính điểm tương thích của mỗi member với mỗi nhóm
        centers = kmeans.cluster_centers_
        
        for i, member in enumerate(members):
            member_scores = []
            for j in range(len(centers)):
                distance = np.linalg.norm(features[i] - centers[j])
                score = 100 * (1 - distance / np.max(np.linalg.norm(features - centers[j], axis=1)))
                member_scores.append({
                    'group_id': j + 1,
                    'score': round(score, 2)
                })
        
        # Phân phối members vào các nhóm theo thuật toán cân bằng
        groups = distribute_to_groups(members, features)
        
        # Tạo kết quả với thông tin chi tiết cho mỗi nhóm
        result = {}
        
        # Hàm helper để chuyển đổi Decimal sang float
        def decimal_to_float(value):
            try:
                if hasattr(value, 'to_eng_string'):  # Kiểm tra nếu là Decimal
                    return float(value.to_eng_string())
                return float(value)
            except (TypeError, ValueError):
                return 'N/A'

        # Tính toán thống kê cho mỗi nhóm
        for group_id, group_members in groups.items():
            # Tính các giá trị trung bình
            valid_gpas = [decimal_to_float(m.get('gpa')) for m in group_members if m.get('gpa') != 'N/A']
            avg_gpa = sum(valid_gpas) / len(valid_gpas) if valid_gpas else 0
            
            # Tạo thông tin chi tiết cho nhóm
            result[f'group_{group_id + 1}'] = {
                'members': [
                    {
                        'id': str(member['id']),
                        'name': member['name'],
                        'gpa': decimal_to_float(member.get('gpa')),
                        'last_gpa': decimal_to_float(member.get('last_gpa')),
                    }
                    for member in group_members
                ],
                'stats': {
                    'avg_gpa': round(avg_gpa, 2),
                }
            }
        
        return json.dumps(result)
        
    except Exception as e:
        return json.dumps({"error": f"General error: {str(e)}"})

if __name__ == "__main__":
    print(main())