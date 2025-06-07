from pydantic import BaseModel
from datetime import datetime
from typing import Optional
from datetime import datetime

class CameraRegister(BaseModel):
    camera_id: int
    room_name: str

class CameraInfo(BaseModel):
    camera_id: int
    room_name: str
    status: str

class DetectionOut(BaseModel):
    camera_id: int
    room_name: str
    timestamp: datetime
    person_count: int
    image_path: str

class UserRegister(BaseModel):
    username: str
    password: str

class UserLogin(BaseModel):
    username: str
    password: str

class TokenResponse(BaseModel):
    token: str

class DetectionOut(BaseModel):
    id: Optional[int]
    camera_id: int
    room_name: str
    timestamp: datetime
    person_count: int
    image_path: str