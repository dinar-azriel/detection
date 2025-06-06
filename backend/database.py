import psycopg2
import os

DB_NAME = os.getenv("DB_NAME", "detection")
DB_USER = os.getenv("DB_USER", "postgres")
DB_PASSWORD = os.getenv("DB_PASSWORD", "root")
DB_HOST = os.getenv("DB_HOST", "localhost")
DB_PORT = os.getenv("DB_PORT", "5432")

try:
    conn = psycopg2.connect(
        dbname=DB_NAME,
        user=DB_USER,
        password=DB_PASSWORD,
        host=DB_HOST,
        port=DB_PORT
    )
    cursor = conn.cursor()
    print("✅ Connected to PostgreSQL database")
except Exception as e:
    print("❌ Failed to connect to PostgreSQL:", e)
    conn = None
    cursor = None
