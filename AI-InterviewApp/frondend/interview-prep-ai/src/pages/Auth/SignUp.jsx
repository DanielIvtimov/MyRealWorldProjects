import React, { useContext, useState } from "react";
import { useNavigate } from "react-router-dom";
import Input from "../../components/Inputs/Input";
import "./styles/SignUp.css";
import ProfilePhotoSelector from "../../components/Inputs/ProfilePhotoSelector";
import { validateEmail } from "../../utils/helper";
import { UserContext } from "../../context/userContext";
import axiosInstace from "../../utils/axiosInstace";
import { API_PATHS } from "../../utils/apiPaths";
import uploadImage from "../../utils/uploadImage";

const SignUp = ({ setCurrentPage }) => {
  const [profilePic, setProfilePic] = useState(null);
  const [fullName, setFullName] = useState("");
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");

  const [error, setError] = useState(null);

  const { updateUser } = useContext(UserContext);

  const navigate = useNavigate();

  const handleSignUp = async (e) => {
    e.preventDefault();

    let profileImageUrl = "";

    if (!fullName) {
      setError("Please enter full name.");
      return;
    }

    if (!validateEmail(email)) {
      setError("Please enter a valid email adress.");
      return;
    }

    if (!password) {
      setError("Please enter a password");
      return;
    }

    setError("");

    //Login API call;
    try{
      if(profilePic){
        const imgUploadResponse = await uploadImage(profilePic);
        profileImageUrl = imgUploadResponse.imageUrl || "";
      }
      const response = await axiosInstace.post(API_PATHS.AUTH.REGISTER, {
        name: fullName,
        email,
        password,
        profileImageUrl,
      });
      const { token } = response.data;
      if(token){
        localStorage.setItem("token", token);
        updateUser(response.data);
        navigate("/dashboard"); 
      }
    }catch(error){
      //Properties from backend server.
      if(error.response && error.response.data.message){
        setError(error.response.data.message);
      }else{
        setError("Something went wrong. Please try again.");
      }
    }
  };

  return (
    <div className="signup-container">
      <h3 className="signup-title">Create an Account</h3>
      <p className="signup-subtitle">
        Join us today by entering your details below.
      </p>
      <form onSubmit={handleSignUp}>
        <ProfilePhotoSelector image={profilePic} setImage={setProfilePic} />

        <div className="input-grid">
          <Input
            value={fullName}
            onChange={({ target }) => setFullName(target.value)}
            label="Full Name"
            placeholder="John"
            type="text"
          />
          <Input
            value={email}
            onChange={({ target }) => setEmail(target.value)}
            label="Email Adress"
            placeholder="john@example.com"
            type="text"
          />
          <Input
            value={password}
            onChange={({ target }) => setPassword(target.value)}
            label="Password"
            placeholder="Min 8 Characters"
            type="password"
          />
        </div>

        {error && <p className="message-error">{error}</p>}
        <button type="submit" className="button-signup">
          SIGN UP
        </button>
        <p className="info-text">
          Already an account?{" "}
          <button
            className="button-loggin"
            onClick={() => {
              setCurrentPage("login");
            }}
          >
            Login
          </button>
        </p>
      </form>
    </div>
  );
};

export default SignUp;
