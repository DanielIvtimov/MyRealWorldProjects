import React, { useState } from 'react'
import { Dialog, DialogContent, DialogFooter, DialogHeader } from './ui/dialog'
import { DialogTitle } from '@radix-ui/react-dialog'
import { Label } from './ui/label'
import { Input } from './ui/input'
import { Button } from './ui/button'
import { Loader2 } from 'lucide-react'
import { useDispatch, useSelector } from 'react-redux'
import axios from 'axios'
import { USER_API_END_POINT } from './utils/constants'
import { toast } from 'sonner'
import { setUser } from './redux/authSlice'

const UpdateProfileDialog = ({ open, setOpen }) => {

    const [loading, setLoading] = useState(false);
    const { user } = useSelector(store => store.auth);

    const [input, setInput] = useState({
        fullname: user?.fullname,
        email: user?.email,
        phoneNumber: user?.phoneNumber,
        bio: user?.profile?.bio,
        skills: user?.profile?.skills.map(skill => skill),
        file: user?.profile?.resume,
    });
    const dispatch = useDispatch();

    const changeEventHandler = (e) => {
        setInput({...input, [e.target.name]: e.target.value});
    }

    const submitHandler = async (e) => {
        e.preventDefault();
        const formData = new FormData();
        formData.append("fullname", input.fullname);
        formData.append("email", input.email);
        formData.append("phoneNumber", input.phoneNumber);
        formData.append("bio", input.bio);
        formData.append("skills", input.skills);
        if(input.file){
            formData.append("file", input.file);
        }
        try{
           const response = await axios.post(`${USER_API_END_POINT}/profile/update`, formData, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
            withCredentials: true,
           });
           if(response.data.success){
                dispatch(setUser(response.data.user));
                toast.success(response.data.success);
           }
        }catch(error){
            console.log(error);
            toast.error(error.response.data.message);
        }
        setOpen(false);
        console.log(input);
    }
    
    const fileChangeHandler = (e) => {
        const file = e.target.files?.[0];
        setInput({...input, file});
    }

  return (
    <div>
        <Dialog open={open} >
            <DialogContent className="sm:max-w-[425px]" onInteractOutside={() => setOpen(false)}>
                <DialogHeader>
                    <DialogTitle>Update Profile</DialogTitle>
                </DialogHeader>
                <form onSubmit={submitHandler}>
                    <div className='grid gap-4 py-4'>
                        <div className='grid grid-cols-4 items-center gap-4 '>
                            <Label htmlFor="name" className="text-right w-1/2">Name</Label>
                            <Input id="fullname" name="fullname" className="col-span-3" value={input.fullname} onChange={changeEventHandler} />  
                        </div>
                        <div className='grid grid-cols-4 items-center gap-4 '>
                            <Label htmlFor="email" className="text-right w-1/2">Email</Label>
                            <Input id="email" name="email" className="col-span-3" value={input.email} onChange={changeEventHandler} />  
                        </div>
                        <div className='grid grid-cols-4 items-center gap-4 '>
                            <Label htmlFor="number" className="text-right w-1/2">Number</Label>
                            <Input id="phoneNumber" name="phoneNumber" className="col-span-3" value={input.phoneNumber} onChange={changeEventHandler} />  
                        </div>
                        <div className='grid grid-cols-4 items-center gap-4 '>
                            <Label htmlFor="bio" className="text-right w-1/2">Bio</Label>
                            <Input id="bio" name="bio" className="col-span-3" value={input.bio} onChange={changeEventHandler} />  
                        </div>
                        <div className='grid grid-cols-4 items-center gap-4 '>
                            <Label htmlFor="skills" className="text-right w-1/2">Skills</Label>
                            <Input id="skills" name="skills" className="col-span-3" value={input.skills} onChange={changeEventHandler} />  
                        </div>
                        <div className='grid grid-cols-4 items-center gap-4 '>
                            <Label htmlFor="resume" className="text-right w-1/2">Resume</Label>
                            <Input id="resume" name="resume" type="file" accept="application/pdf" className="col-span-3" onChange={fileChangeHandler} />  
                        </div>
                    </div>
                    <DialogFooter>
                        {
                            loading ? (
                                <Button className="w-full my-4"><Loader2 className='mr-2 h-2 w-4 animate-spin' /> Please wait </Button> 
                            ) : (
                                <Button type="submit" className="w-full my-4">Update</Button>
                            )   
                        }
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>
    </div>
  )
}

export default UpdateProfileDialog