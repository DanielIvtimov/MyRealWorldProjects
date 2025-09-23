import { setAllAppliedJobs } from '@/components/redux/jobSlice';
import { APPLICATION_API_END_POINT } from '@/components/utils/constants';
import axios from 'axios';
import { useEffect } from 'react'
import { useDispatch } from 'react-redux'

const useGetAppliedJobs = () => {

    const dispatch = useDispatch();

    useEffect(() => {
        const fetchAppliedJobs = async () => {
            try{
               const response = await axios.get(`${APPLICATION_API_END_POINT}/get-appliedJobs`, {
                withCredentials: true
               });
               console.log("Data is:", response.data);
               if(response.data.success){
                dispatch(setAllAppliedJobs(response.data.application));
               }
            }catch(error){
                console.log(error);
            }
        }
        fetchAppliedJobs();
    }, [])
}

export default useGetAppliedJobs