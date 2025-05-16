import React, { useContext, useState } from 'react'
import AuthLayout from '../../components/layout/AuthLayout';
import ProfilePhotoSelector from '../../components/input/ProfilePhotoSelector';
import {Link, useNavigate } from "react-router-dom";
import AuthInput from '../../components/input/AuthInput';
import { validateEmail } from '../../utils/helper';
import "./styles/SignUpForm.css";
import { UserContext } from '../../context/UserContext';
import axiosInstance from '../../utils/axiosinstance';
import { API_PATHS } from '../../utils/apiPaths';
import uploadImage from '../../utils/uploadImage';

const SignUpForm = () => {

  const [profilePic, setProfilePic] = useState(null);
  const [fullName, setFullName] = useState("");
  const [email, setEmail] = useState("");
  const [username, setUsername] = useState("");
  const [password, setPassword] = useState("");
  const [error, setError] = useState(null);

  const {updateUser} = useContext(UserContext);
  const navigate = useNavigate();

  const handleSignUp = async (e) => {
    e.preventDefault();

    let profileImageUrl = "";

    if(!fullName){
      setError("Please enter the full name");
      return;
    }
    if(!validateEmail(email)){
      setError("Please enter a valid email adress.")
      return;
    }
    if(!username){
      setError("Please enter username")
      return;
    }
    if(!password){
      setError("Please enter the password");
      return;
    }
    setError("");
    try{

      if(profilePic){
        const imgUploadResponse = await uploadImage(profilePic);
        profileImageUrl = imgUploadResponse.imageUrl || "";
      }

      const response = await axiosInstance.post(API_PATHS.AUTH.REGISTER, {
        fullName,
        username,
        email,
        password,
        profileImageUrl
      })

      const {token, user} = response.data;
      if(token){
        console.log(token);
        localStorage.setItem("token", token);
        updateUser(user);
        navigate("/login");
      }
    }catch(error){
      console.log(error);
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
        <div className='signup-form-container'>
          <h3 className='signup-heading'>Create an Account</h3>
          <p className='signup-text'>Join us today by entering your details below.</p>

          <form onSubmit={handleSignUp}>
            <ProfilePhotoSelector image={profilePic} setImage={setProfilePic} />
            <div className='signup-grid-container'>
              <AuthInput value={fullName} onChange={({target }) => setFullName(target.value)} label="Full name" placeholder="John" type="text" />
              <AuthInput value={email} onChange={({ target }) => setEmail(target.value)} label="Email Adress" placeholder="john@example.com" type="text"></AuthInput>
              <AuthInput value={username} onChange={({ target }) => setUsername(target.value)} label="Username" placeholder="@" type="text"></AuthInput>
              <AuthInput value={password} onChange={({ target }) => setPassword(target.value)} label="Password" placeholder="Min 8 Characters" type="password"></AuthInput>
            </div>
            {error && <p className='error-message'>{error}</p>}
            <button type='submit' className='btn-primary'>CREATE ACCOUNT</button>
            <p className='login-message'>Already have an account ? {" "} <Link className='signup-redirect-link' to="/login">Login</Link></p>
          </form>
        </div>
      </AuthLayout>
    </>
  )
}

export default SignUpForm