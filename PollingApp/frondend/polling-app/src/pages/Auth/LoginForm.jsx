import React, { useState } from 'react'
import "./styles/LoginForm.css";
import AuthLayout from '../../components/layout/AuthLayout';
import AuthInput from '../../components/input/AuthInput';
import { Link, useNavigate } from "react-router-dom";
import { validateEmail } from '../../utils/helper';
import axiosInstance from '../../utils/axiosinstance';
import { API_PATHS } from '../../utils/apiPaths';
import { useContext } from 'react';
import { UserContext } from '../../context/UserContext';

const LoginForm = () => {

  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [error, setError] = useState(null);

  const {updateUser} = useContext(UserContext);
  const navigate = useNavigate();

  const handleLogin = async (e) => {
    e.preventDefault();
    if(!validateEmail(email)){
      setError("Please enter a valid email adress.");
      return;
    }
    if(!password){
      setError("Please enter the password");
      return;
    }
    setError("");

    try{
      const response = await axiosInstance.post(API_PATHS.AUTH.LOGIN, {
        email,
        password,
      });
      const { token, user } = response.data;
      if(token){
        console.log({ token, user }); 
        localStorage.setItem("token", token);
        updateUser(user);
        navigate("/dashboard");
      } 
    }catch(error){
      if(error.response && error.response.data.message){
        setError(error.response.data.message);
      }else{
        setError("Something went wrong. Please try again");
      }
    }
  };
 
  return (
    <>
      <AuthLayout>
        <div className='login-container'>
          <h3 className='login-title'>Welcome Back</h3>
          <p className='login-message'>Please enter your details to log in</p>

          <form onSubmit={handleLogin}>
            <AuthInput value={email} onChange={({ target }) => setEmail(target.value)} label="Email Adress" placeholder="john@example.com" type="text"></AuthInput>
            <AuthInput value={password} onChange={({ target }) => setPassword(target.value)} label="Password" placeholder="Min 8 Characters" type="password"></AuthInput>
            {error && <p className='error-message'>{error}</p>}
            <button type='submit' className='btn-primary'>Login</button>
            <p className='login-message'>Don't have an account ? {" "} <Link className='signup-redirect-link' to="/signup">SignUp</Link></p>
          </form>
        </div>
      </AuthLayout>
    </>
  )
}

export default LoginForm