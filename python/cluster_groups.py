import mysql.connector
import numpy as np
from sklearn.preprocessing import StandardScaler
from sklearn.cluster import KMeans
import json

def connect_database():
    """Kết nối với MySQL database"""
    return mysql.connector.connect(
        host="localhost",
        user="root",  # Thường mặc định là root
        password="",  # Password trống
        database="cluster"
    )

def fetch_members():
    """Lấy dữ liệu từ bảng member"""
    try:
        conn = connect_database()
        cursor = conn.cursor(dictionary=True)
        cursor.execute("SELECT * FROM member")
        members = cursor.fetchall()
        cursor.close()
        conn.close()
        return members
    except Exception as e:
        print(f"Error fetching data: {str(e)}")
        return []

def prepare_data(members):
    """Chuẩn bị dữ liệu cho clustering"""
    try:
        features = []
        for member in members:
            feature = [
                float(member['gpa']),
                float(member['last_gpa']),
                float(member['final_score'])
            ]
            features.append(feature)
        
        if len(features) == 0:
            return None
            
        scaler = StandardScaler()
        features_scaled = scaler.fit_transform(features)
        return features_scaled
    except Exception as e:
        print(f"Error preparing data: {str(e)}")
        return None

def create_balanced_groups(members, features_scaled, n_groups=4):
    """Tạo các nhóm cân bằng dựa trên clustering"""
    try:
        n_members = len(members)
        members_per_group = n_members // n_groups
        
        kmeans = KMeans(n_clusters=n_groups, random_state=42)
        clusters = kmeans.fit_predict(features_scaled)
        
        cluster_members = {i: [] for i in range(n_groups)}
        for i, cluster_id in enumerate(clusters):
            cluster_members[cluster_id].append(members[i])
        
        final_groups = {i: [] for i in range(n_groups)}
        remaining_members = []
        
        for cluster_id, cluster_list in cluster_members.items():
            for member in cluster_list:
                if len(final_groups[cluster_id]) < members_per_group:
                    final_groups[cluster_id].append(member)
                else:
                    remaining_members.append(member)
        
        for member in remaining_members:
            for group_id in range(n_groups):
                if len(final_groups[group_id]) < members_per_group:
                    final_groups[group_id].append(member)
                    break
        
        return final_groups
    except Exception as e:
        print(f"Error creating groups: {str(e)}")
        return None

def main():
    """Hàm chính để thực hiện chia nhóm"""
    try:
        # Lấy dữ liệu từ database
        members = fetch_members()
        if not members:
            return json.dumps({"error": "No members found"})
        
        # Chuẩn bị dữ liệu
        features_scaled = prepare_data(members)
        if features_scaled is None:
            return json.dumps({"error": "Error preparing data"})
        
        # Tạo nhóm
        groups = create_balanced_groups(members, features_scaled)
        if groups is None:
            return json.dumps({"error": "Error creating groups"})
        
        # Chuyển đổi decimal và datetime objects thành string để có thể serialize
        result = {}
        for group_id, group_members in groups.items():
            result[f'group_{group_id + 1}'] = [
                {
                    'id': str(member['id']),
                    'name': member['name'],
                    'gpa': str(member['gpa']),
                    'last_gpa': str(member['last_gpa']),
                    'final_score': str(member['final_score']),
                    'personality': member['personality'],
                    'hobby': member['hobby']
                }
                for member in group_members
            ]
        
        # Trả về JSON string
        return json.dumps(result)
        
    except Exception as e:
        return json.dumps({"error": f"General error: {str(e)}"})

if __name__ == "__main__":
    print(main())  # In ra để PHP có thể capture output