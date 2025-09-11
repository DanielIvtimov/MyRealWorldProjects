import { setSingleCompany } from '@/components/redux/companySlice'
import { COMPANY_API_END_POINT } from '@/components/utils/constants'
import axios from 'axios'
import { useEffect } from 'react'
import { useDispatch } from 'react-redux'

const useGetCompanyById = (companyId) => {

    const dispatch = useDispatch();

    useEffect(() => {
        const fetchSingleCompany = async () => {
            try{
               const response = await axios.get(`${COMPANY_API_END_POINT}/get-company/${companyId}`, {
                withCredentials: true,
               });
               if(response.data.success){
                    dispatch(setSingleCompany(response.data.company)); 
               }
            }catch(error){
                console.log(error);
            }
        }
        fetchSingleCompany();
    }, [companyId, dispatch])

  return (
    <div></div>
  )
}

export default useGetCompanyById;