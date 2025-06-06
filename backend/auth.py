from fastapi import APIRouter, HTTPException
from models import UserRegister, UserLogin, TokenResponse
from database import cursor, conn
import hashlib
import jwt
import os

router = APIRouter()
SECRET_KEY = os.getenv("JWT_SECRET", "secret")

def create_token(username: str):
    payload = {"username": username}
    return jwt.encode(payload, SECRET_KEY, algorithm="HS256")

@router.post("/register")
def register(data: UserRegister):
    password_hash = hashlib.sha256(data.password.encode()).hexdigest()
    try:
        cursor.execute(
            "INSERT INTO users (username, password) VALUES (%s, %s)",
            (data.username, password_hash)
        )
        conn.commit()
    except:
        raise HTTPException(status_code=400, detail="Username already exists")
    return {"message": "User registered"}

@router.post("/login", response_model=TokenResponse)
def login(data: UserLogin):
    password_hash = hashlib.sha256(data.password.encode()).hexdigest()
    cursor.execute(
        "SELECT * FROM users WHERE username = %s AND password = %s",
        (data.username, password_hash)
    )
    user = cursor.fetchone()
    if not user:
        raise HTTPException(status_code=401, detail="Invalid credentials")
    token = create_token(data.username)
    return {"token": token}
