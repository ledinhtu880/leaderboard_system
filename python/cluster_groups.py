import mysql.connector
import numpy as np
from sklearn.preprocessing import StandardScaler
from collections import defaultdict
import json
from decimal import Decimal  # Thêm import Decimal

def connect_database():
    """Kết nối với MySQL database"""
    return mysql.connector.connect(
        host="localhost",
        user="root",
        password="",
        database="cluster_backup"
    )

def fetch_members():
    """Lấy dữ liệu từ bảng member"""
    try:
        conn = connect_database()
        cursor = conn.cursor(dictionary=True)
        cursor.execute("""
            SELECT 
                members.id, 
                members.name, 
                members.gpa, 
                members.last_gpa, 
                members.subject_1_mark, 
                members.subject_2_mark, 
                members.subject_3_mark, 
                member_topics.topic_id, 
                topics.description,
                topics.name as topic_name
            FROM members
            LEFT JOIN member_topics ON members.id = member_topics.member_id
            LEFT JOIN topics ON member_topics.topic_id = topics.id
        """)
        members = cursor.fetchall()
        cursor.close()
        conn.close()
        return members
    except Exception as e:
        print(f"Error fetching data: {str(e)}")
        return []


def convert_decimal_to_float(data):
    """Chuyển đổi các giá trị Decimal trong cấu trúc dữ liệu thành float"""
    if isinstance(data, list):
        return [convert_decimal_to_float(item) for item in data]
    elif isinstance(data, dict):
        return {key: convert_decimal_to_float(value) for key, value in data.items()}
    elif isinstance(data, Decimal):
        return float(data)
    else:
        return data


def prepare_data(members):
    """Chuẩn bị dữ liệu cho clustering với trọng số mới"""
    try:
        if not members:
            return None
        
        # Chuẩn bị các trọng số
        weights = {
            "gpa": 0.4,
            "last_gpa": 0.3,
            "subject_1_mark": 0.1,
            "subject_2_mark": 0.1,
            "subject_3_mark": 0.1
        }
        
        # Chuẩn hóa dữ liệu số
        numeric_features = np.array([
            [
                float(m['gpa']),
                float(m['last_gpa']),
                float(m['subject_1_mark']),
                float(m['subject_2_mark']),
                float(m['subject_3_mark'])
            ]
            for m in members
        ])
        
        scaler = StandardScaler()
        numeric_scaled = scaler.fit_transform(numeric_features)
        
        # Tính điểm tổng hợp với trọng số
        weighted_features = []
        for i in range(len(members)):
            feature = np.dot(
                numeric_scaled[i],
                [weights['gpa'], weights['last_gpa'], weights['subject_1_mark'], weights['subject_2_mark'], weights['subject_3_mark']]
            )
            weighted_features.append(feature)
        
        return np.array(weighted_features)
    except Exception as e:
        print(f"Error preparing data: {str(e)}")
        return None
    
def distribute_to_topics(members, max_members_per_topic=5):
    try:
        # Kết nối database để lấy danh sách topic
        conn = connect_database()
        cursor = conn.cursor(dictionary=True)
        cursor.execute("SELECT id, description, name FROM topics")
        topics = cursor.fetchall()
        cursor.close()
        conn.close()
        
        # Khởi tạo các nhóm trống
        topic_groups = {topic['id']: [] for topic in topics}
        topic_descriptions = {topic['id']: topic['description'] for topic in topics}
        topic_names = {topic['id']: topic['name'] for topic in topics}
        
        # Phân loại thành viên theo topic
        overflow_members = []  # Thành viên dư thừa
        for member in members:
            topic_id = member['topic_id']
            if topic_id is not None and topic_id in topic_groups:
                topic_groups[topic_id].append(member)
            else:
                overflow_members.append(member)  # Thành viên chưa chọn topic
        
        # Xử lý các nhóm quá tải
        for topic_id, group in list(topic_groups.items()):
            if len(group) > max_members_per_topic:
                overflow_members.extend(group[max_members_per_topic:])  # Thành viên dư thừa
                topic_groups[topic_id] = group[:max_members_per_topic]  # Giữ lại tối đa `max_members_per_topic` thành viên
        
        # Tính điểm trung bình cho toàn cụm
        overall_avg = np.mean([prepare_data([m])[0] for m in members])

        # Phân phối lại thành viên dư thừa vào các nhóm còn chỗ trống
        for member in overflow_members:
            best_topic = None
            smallest_diff = float('inf')
            
            for topic_id, group in topic_groups.items():
                if len(group) < max_members_per_topic:  # Chỉ chọn các nhóm còn chỗ trống
                    current_avg = np.mean([prepare_data([m])[0] for m in group]) if group else overall_avg
                    new_avg = (current_avg * len(group) + prepare_data([member])[0]) / (len(group) + 1)
                    diff = abs(new_avg - overall_avg)
                    
                    # Chọn nhóm có sự thay đổi điểm trung bình nhỏ nhất
                    if diff < smallest_diff:
                        best_topic = topic_id
                        smallest_diff = diff
            
            # Phân bổ thành viên vào nhóm tốt nhất
            if best_topic is not None:
                topic_groups[best_topic].append(member)
        
        return topic_groups, topic_descriptions, topic_names
    except Exception as e:
        print(f"Error distributing topics: {str(e)}")
        return None, None, None


def calculate_topic_averages(topic_groups):
    """Tính điểm trung bình cho mỗi topic và toàn cụm"""
    topic_averages = {}
    total_score = 0
    total_members = 0

    for topic_id, members in topic_groups.items():
        topic_score = 0
        for member in members:
            # Chuyển đổi giá trị Decimal sang float trước khi tính toán
            gpa = float(member['gpa'])
            last_gpa = float(member['last_gpa'])
            subject_1_mark = float(member['subject_1_mark'])
            subject_2_mark = float(member['subject_2_mark'])
            subject_3_mark = float(member['subject_3_mark'])

            # Tính điểm tổng hợp
            score = (
                gpa * 0.4 +
                last_gpa * 0.3 +
                subject_1_mark * 0.1 +
                subject_2_mark * 0.1 +
                subject_3_mark * 0.1
            )
            topic_score += score

        topic_average = topic_score / len(members) if members else 0
        topic_averages[topic_id] = round(topic_average, 2)

        # Tính tổng điểm và số lượng thành viên cho toàn cụm
        total_score += topic_score
        total_members += len(members)

    # Tính điểm trung bình toàn cụm
    overall_average = round(total_score / total_members, 2) if total_members > 0 else 0
    return topic_averages, overall_average

def main():
    """Hàm chính để thực hiện chia nhóm và trả kết quả JSON cho Laravel"""
    try:
        # Lấy dữ liệu từ database
        members = fetch_members()
        if not members:
            return {"error": "Không có dữ liệu thành viên"}
        
        # Phân phối thành viên vào các topic
        topic_groups, topic_descriptions, topic_names = distribute_to_topics(members)
        if not topic_groups:
            return {"error": "Không thể phân nhóm thành viên"}
        
        # Tính điểm trung bình cho từng topic và toàn cụm
        topic_averages, overall_average = calculate_topic_averages(topic_groups)
        
        # Chuẩn bị kết quả JSON
        result = {
            "topic_groups": {
                topic_id: {
                    "description": topic_descriptions.get(topic_id),  # Thêm description vào output
                    "name": topic_names.get(topic_id),  # Thêm description vào output
                    "members": [
                        {
                            "id": member["id"],
                            "name": member["name"],
                            "gpa": member["gpa"],
                            "last_gpa": member["last_gpa"],
                            "subject_1_mark": member["subject_1_mark"],
                            "subject_2_mark": member["subject_2_mark"],
                            "subject_3_mark": member["subject_3_mark"]
                        } for member in members
                    ]
                } for topic_id, members in topic_groups.items()
            },
            "topic_averages": topic_averages,
            "overall_average": overall_average
        }
        
        # Chuyển đổi Decimal sang float trước khi serialize JSON
        result = convert_decimal_to_float(result)
        return json.dumps(result, ensure_ascii=False, indent=4)
    except Exception as e:
        return json.dumps({"error": f"Lỗi chung: {str(e)}"}, ensure_ascii=False)


if __name__ == "__main__":
    # Đảm bảo in ra với UTF-8
    import sys
    sys.stdout.reconfigure(encoding='utf-8')
    print(main())
