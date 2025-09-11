import { setCompanies } from '@/components/redux/companySlice';
import { COMPANY_API_END_POINT } from '@/components/utils/constants';
import axios from 'axios';
import React, { useEffect } from 'react'
import { useDispatch } from 'react-redux'

const useGetAllCompanies = () => {

    const dispatch = useDispatch();

    useEffect(() => {
        const fetchAllCompanies = async () => {
            try{
               const response = await axios.get(`${COMPANY_API_END_POINT}/get-company`, {
                withCredentials: true
               });
               if(response.data.success){
                dispatch(setCompanies(response.data.companies));
               }
            }catch(error){
                console.log(error);   
            }
        }
        fetchAllCompanies();
    }, []);

  return (
    <div></div>
  )
}

export default useGetAllCompanies