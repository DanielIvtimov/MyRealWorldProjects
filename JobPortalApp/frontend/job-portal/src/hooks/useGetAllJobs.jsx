import { setAllJobs } from '@/components/redux/jobSlice'
import { JOB_API_END_POINT } from '@/components/utils/constants'
import axios from 'axios'
import { useEffect } from 'react'
import { useDispatch, useSelector } from 'react-redux'

const useGetAllJobs = () => {

    const dispatch = useDispatch();

    const { searchQuery } = useSelector(store => store.job);
 
    useEffect(() => {
        const fetchAllJobs = async () => {
            try{
               const response = await axios.get(`${JOB_API_END_POINT}/get-jobs?keyword=${searchQuery}`, {
                withCredentials: true,
               });
               if(response.data.success){
                    dispatch(setAllJobs(response.data.jobs));
               }
            }catch(error){
              if(error.response && error.response.status === 404){
                dispatch(setAllJobs([]));
                console.log("No jobs found, setting allJobs to empty array");
              }else{
                console.error(error);
              }
            }
        }
        fetchAllJobs();
    }, []);

  return (
    <div></div>
  )
}

export default useGetAllJobs