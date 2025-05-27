import React, { useState } from 'react'
import { useNavigate } from 'react-router-dom';
import "./styles/Login.css"
import Input from '../../components/Inputs/Input';

const Login = ({setCurrentPage}) => {

  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [error, setError] = useState(null);

  const navigate = useNavigate();

  const handleLogin = async (e) => {
    e.praventDefault();
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