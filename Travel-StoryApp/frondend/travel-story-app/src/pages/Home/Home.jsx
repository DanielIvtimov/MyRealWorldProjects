import React, { useEffect, useState } from 'react'
import Navbar from '../../components/Input/Navbar'
import { useNavigate } from "react-router-dom";
import axiosInstace from "../../utils/axiosInstace.js"

const Home = () => {

  const [userInfo, setUserInfo] = useState(null);

  const navigate = useNavigate()

  const getUserInfo = async () => {
    try{
      const response = await axiosInstace.get("/api/auth/get-user");
      if(response.data && response.data.user){
        setUserInfo(response.data.user);
      }
    }catch(error){
      if(error.response.status === 401){
        localStorage.clear();
        navigate("/login");
      }
    }
  }

  useEffect(() => {
    getUserInfo();
  }, [])

  return (
    <>
      <Navbar userInfo={userInfo} />
    </>
  )
}

export default Home