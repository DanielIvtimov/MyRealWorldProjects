import { Popover, PopoverContent, PopoverTrigger } from "@radix-ui/react-popover";
import React, { use } from "react";
import { Avatar, AvatarImage } from "@radix-ui/react-avatar";
import { Button } from "../ui/button";
import { LogOut, User2 } from "lucide-react";
import { Link } from "react-router-dom";
import { useSelector } from "react-redux";

const Navbar = () => {

    const { user } = useSelector(store => store.auth);

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
                                        <AvatarImage src="https://github.com/shadcn.png" alt="@shadcn" className="rounded-full" />
                                    </Avatar>
                                </PopoverTrigger>
                                <PopoverContent className="w-80 p-4 rounded-md border border-gray-150 shadow-xl bg-white ">
                                    <div className="flex gap-4">
                                        <Avatar className="w-10 h-10 cursor-pointer">
                                            <AvatarImage src="https://github.com/shadcn.png" alt="@shadcn" className="rounded-full" />
                                        </Avatar>
                                        <div className="space-y-2">
                                            <h4 className="font-medium">Daniel MernStack</h4>
                                            <p className="text-sm text-muted-foreground">Lorem ipsum dolor sit amet.</p>
                                        </div>
                                    </div>
                                    <div className="flex flex-col my-2 text-gray-600">
                                        <div className="flex w-fit items-center gap-2">
                                            <User2 />
                                            <Button variant="link" className="cursor-pointer"> <Link to="/profile">View Profile</Link></Button>
                                        </div>
                                        <div className="flex w-fit items-center gap-2">
                                            <LogOut />
                                            <Button variant="link" className="cursor-pointer">Logout</Button>
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
