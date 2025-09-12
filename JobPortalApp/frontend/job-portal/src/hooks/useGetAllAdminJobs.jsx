import { setAllAdminJobs } from '@/components/redux/jobSlice'
import { JOB_API_END_POINT } from '@/components/utils/constants'
import axios from 'axios'
import { useEffect } from 'react'
import { useDispatch } from 'react-redux'

const useGetAllAdminJobs = () => {

    const dispatch = useDispatch();

    useEffect(() => {
        const fetchAllAdminJobs = async () => {
            try{
               const response = await axios.get(`${JOB_API_END_POINT}/getAdminJobs`, {
                withCredentials: true,
               });
               if(response.data.success){
                    console.log("Response is:", response.data.success);
                    dispatch(setAllAdminJobs(response.data.jobs));
               }
            }catch(error){
                console.log(error);
            }
        }
        fetchAllAdminJobs();
    }, [])

  return (
    <div></div>
  )
}

export default useGetAllAdminJobs;