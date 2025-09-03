import { Popover, PopoverContent, PopoverTrigger } from "@radix-ui/react-popover";
import React from "react";
import { Avatar, AvatarImage } from "@radix-ui/react-avatar";
import { Button } from "../ui/button";
import { LogOut, User2 } from "lucide-react";
import { Link, useNavigate } from "react-router-dom";
import { useDispatch, useSelector } from "react-redux";
import { toast } from "sonner";
import axios from "axios";
import { USER_API_END_POINT } from "../utils/constants";
import { setUser } from "../redux/authSlice";

const Navbar = () => {

    const { user } = useSelector(store => store.auth);
    const dispatch = useDispatch();
    const navigate = useNavigate();

    const logoutHandler = async () => {
        try{
            const response = await axios.get(`${USER_API_END_POINT}/logout`, {
                withCredentials: true
            });
            if(response.data.success){
                dispatch(setUser(null));
                navigate("/");
                toast.success(response.data.message);
            }
        }catch(error){
            console.log(error);
            toast.error(error.response.data.message);
        }
    }

    return (
        <div className="bg-white">
            <div className="flex items-center justify-between mx-auto max-w-7xl h-16">
                <div>
                    <h1 className="text-2xl font-bold">
                        Job<span className="text-[#F83002]">Portal</span>
                    </h1>
                </div>
                <div className="flex items-center gap-12">
                    <ul className="flex font-medium items-center gap-5">
                        <li><Link to="/">Home</Link></li>
                        <li><Link to="/jobs">Jobs</Link></li>
                        <li><Link to="/browse">Browse</Link></li>
                    </ul>
                    {
                        !user ? (
                            <div className="flex items-center gap-2">
                                <Link to="/login">
                                    <Button variant="outline" className="cursor-pointer">Login</Button>
                                </Link>
                                <Link to="/signup">
                                    <Button className="bg-[#6A38C2] hover:bg-[#5b30a6]">SignUp</Button>
                                </Link>
                            </div>
                        ) : (
                            <Popover>
                                <PopoverTrigger asChild>
                                    <Avatar className="w-10 h-10 cursor-pointer">
                                        <AvatarImage src={user?.profile?.profilePhoto} alt="@shadcn" className="w-full h-full object-cover rounded-full" />
                                    </Avatar>
                                </PopoverTrigger>
                                <PopoverContent className="w-80 p-4 rounded-md border border-gray-150 shadow-xl bg-white ">
                                    <div className="flex gap-4">
                                        <Avatar className="w-10 h-10 cursor-pointer">
                                            <AvatarImage src={user?.profile?.profilePhoto} alt="@shadcn" className="w-full h-full object-cover rounded-full" />
                                        </Avatar>
                                        <div className="space-y-2">
                                            <h4 className="font-medium">{user?.fullname}</h4>
                                            <p className="text-sm text-muted-foreground">{user?.profile?.bio}</p>
                                        </div>
                                    </div>
                                    <div className="flex flex-col my-2 text-gray-600">
                                        <div className="flex w-fit items-center gap-2">
                                            <User2 />
                                            <Button variant="link" className="cursor-pointer"> <Link to="/profile">View Profile</Link></Button>
                                        </div>
                                        <div className="flex w-fit items-center gap-2">
                                            <LogOut />
                                            <Button onClick={logoutHandler} variant="link" className="cursor-pointer">Logout</Button>
                                        </div>
                                    </div>
                                </PopoverContent>
                            </Popover>
                        )
                    }
                </div>
            </div>
        </div>
    );
};

export default Navbar;
