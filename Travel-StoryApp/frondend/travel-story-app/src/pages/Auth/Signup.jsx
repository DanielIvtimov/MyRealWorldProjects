import React, { useState } from 'react'
import "./styles/Signup.css";
import PasswordInput from '../../components/Input/PasswordInput';
import { useNavigate } from 'react-router-dom';
import { validateEmail } from '../../utils/helper';
import axiosInstance from '../../utils/axiosInstace';

const Signup = () => {
  const [name, setName] = useState("");
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [error, setError] = useState(null);
  const navigate = useNavigate();

  const handleSignUp = async (event) => {
    event.preventDefault();

    if(!name){
      setError("Please enter your name");
      return;
    }

    if(!validateEmail(email)){
      setError("Please enter a valid email adress");
      return;
    }

    if(!password){
      setError("Please enter the password");
      return;
    }

    setError("");

    try{
      const response = await axiosInstance.post("/api/auth/create-account", {
        fullName: name,
        email: email,
        password: password,
      });
      if(response.data && response.data.accessToken){
        localStorage.setItem("token", response.data.accessToken);
        navigate("/login"); 
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
    <div className="signup-container">
      <div className="signup-iu-box" />
      <div className="signup-iu-box-two" />
      <div className="signup-content-wrapper">
        <div className="signup-visual-panel">
          <div>
            <h4 className="signup-headline">
              Join the <br /> Adventure
            </h4>
            <p className="signup-subtext">
              Create an account to start documenting your travels and preserving your memories in your personal travel journal.
            </p>
          </div>
        </div>

        <div className="signup-form-panel">
          <form onSubmit={handleSignUp}>
            <h4 className="signup-title">SignUp</h4>
            <input type="text" placeholder="Full Name" className="input-box-name" value={name} onChange={({ target }) => { setName(target.value) }} />
            <input type="text" placeholder="Email" className="input-box-email" value={email} onChange={({ target }) => { setEmail(target.value) }} />
            <PasswordInput value={password} onChange={({ target }) => { setPassword(target.value) }} />
            {error && <p className="form-error-message">{error}</p>}
            <button type="submit" className="login-button">CREATE ACCOUNT</button>
            <p className="form-divider-text">Or</p>
            <button type="button" className="signup-button" onClick={() => { navigate("/login"); }}>Login</button>
          </form>
        </div>
      </div>
    </div>
  )
}

export default Signup;