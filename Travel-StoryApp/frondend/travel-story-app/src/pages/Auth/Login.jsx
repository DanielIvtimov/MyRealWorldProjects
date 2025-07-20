import React, { useState } from 'react'
import "./styles/Login.css"
import PasswordInput from '../../components/Input/PasswordInput';
import { useNavigate } from 'react-router-dom';
import { validateEmail } from '../../utils/helper';
import axiosInstance from '../../utils/axiosInstace';

const Login = () => {

  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [error, setError] = useState(null);

  const navigate = useNavigate()

  const handleLogin = async (event) => {
    event.preventDefault();
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
      const response = await axiosInstance.post("/api/auth/login-account", {
        email: email,
        password: password,
      });
      if(response.data && response.data.accessToken){
        localStorage.setItem("token", response.data.accessToken);
        navigate("/dashboard"); 
      }
    }catch(error){
      if(error.response && error.response.data && error.response.data.message){
        setError(error.response.data.message)
      } else {
        setError("An unexpected error occured. Please try again.");
      }
    }
  }

  return (
    <div className="login-container">
      <div className="login-iu-box" />
      <div className="login-iu-box-two" />
      <div className="login-content-wrapper">
        <div className="login-visual-panel">
          <div>
            <h4 className="login-headline">
              Capture Your <br /> Journeys
            </h4>
            <p className="login-subtext">
              Record your travel experiences and memories in your personal
              travel journal.
            </p>
          </div>
        </div>

        <div className="login-form-panel">
          <form onSubmit={handleLogin}>
            <h4 className="login-title">Login</h4>
            <input type="text" placeholder="Email" className="input-box" value={email} onChange={({target}) => {setEmail(target.value)}} />
            <PasswordInput value={password} onChange={({target}) => {setPassword(target.value)}} />
            {error && <p className="form-error-message">{error}</p>}
            <button type="submit" className="login-button">Login</button>
            <p className="form-divider-text">Or</p>
            <button type="submit" className="signup-button" onClick={() => {navigate("/signup");}}>CREATE ACCOUNT</button>
          </form>
        </div>
      </div>
    </div>
  )
}

export default Login