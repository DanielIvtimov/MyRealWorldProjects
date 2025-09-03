import { setAllJobs } from '@/components/redux/jobSlice'
import { JOB_API_END_POINT } from '@/components/utils/constants'
import axios from 'axios'
import { useEffect } from 'react'
import { useDispatch } from 'react-redux'

const useGetAllJobs = () => {

    const dispatch = useDispatch();

    useEffect(() => {
        const fetchAllJobs = async () => {
            try{
               const response = await axios.get(`${JOB_API_END_POINT}/get-jobs`, {
                withCredentials: true,
               });
               if(response.data.success){
                    dispatch(setAllJobs(response.data.jobs));
               }
            }catch(error){
                console.log(error);
            }
        }
        fetchAllJobs();
    }, [])

  return (
    <div></div>
  )
}

export default useGetAllJobs