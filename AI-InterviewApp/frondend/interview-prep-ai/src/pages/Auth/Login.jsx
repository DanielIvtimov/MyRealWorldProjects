import React, { useContext, useState } from 'react'
import { useNavigate } from 'react-router-dom';
import "./styles/Login.css"
import Input from '../../components/Inputs/Input';
import { validateEmail } from '../../utils/helper';
import axiosInstace from '../../utils/axiosInstace';
import { API_PATHS } from '../../utils/apiPaths';
import { UserContext } from '../../context/userContext';

const Login = ({setCurrentPage}) => {

  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [error, setError] = useState(null);

  const { updateUser } = useContext(UserContext);
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

    //Login API call;
    try{
      const response = await axiosInstace.post(API_PATHS.AUTH.LOGIN, {
        email, password,
      });
      const { token } = response.data;
      if(token){
        localStorage.setItem("token", token);
        updateUser(response.data);
        navigate("/dashboard");
      };
    }catch(error){
      //Properties from backend server.
      if(error.response && error.response.data.message){
        setError(error.response.data.message);
      } else {
        setError("Something went wrong. Please try again.");
      }
    }
  };

  return (
    <div className="login-container">
      <h3 className="login-title">Welcome Back</h3>
      <p className="login-subtitle">
        Please enter your details to log in
      </p>
      <form onSubmit={handleLogin}>
        <Input
          value={email}
          onChange={({target}) => setEmail(target.value)}
          label="Email Adress"
          placeholder="john@example.com"
          type="text"
        />
        <Input
          value={password}
          onChange={({target}) => setPassword(target.value)}
          label="Password"
          placeholder="Min 8 Characters"
          type="password"
        />

        {error && <p className="error-message">{error}</p>}
        <button type="submit" className="login-button">LOGIN</button>
        <p className="signup-text">
          Don't have an account ? {" "}
          <button 
          className="signup-button"
          onClick={() => {
            setCurrentPage("signup");
          }}
          >
            SignUp
          </button>
        </p>
      </form>
    </div>
  )
}

export default Login