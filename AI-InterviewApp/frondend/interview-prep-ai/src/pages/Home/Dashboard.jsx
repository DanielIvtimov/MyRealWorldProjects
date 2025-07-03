import React, { useEffect, useState } from 'react'
import { LuPlus } from "react-icons/lu";
import { CARD_BG } from "../../utils/data";
import toast from "react-hot-toast";
import DashboardLayout from '../../components/layouts/DashboardLayout';
import { useNavigate } from 'react-router-dom';
import "./styles/Dashboard.css"
import axiosInstace from '../../utils/axiosInstace';
import { API_PATHS } from '../../utils/apiPaths';
import SummaryCard from '../../components/Cards/SummaryCard';
import moment from "moment"
import Modal from '../../components/Modal';
import CreateSessionForm from './CreateSessionForm';
import DeleteAlertContent from '../../components/DeleteAlertContent';
 
const Dashboard = () => {

  const navigate = useNavigate();

  const [openCreateModel, setOpenCreateModel] = useState(false);
  const [sessions, setSessions] = useState([]);

  const [openDeleteAlert, setOpenDeleteAlert] = useState({
    open: false,
    data: null,
  });

  const fetchAllSessions = async () => {
    try{
      const response = await axiosInstace.get(API_PATHS.SESSION.GET_ALL);
      setSessions(response.data);
    }catch(error){
      console.error("Error fetching session data", error); 
    }
  };

  const deleteSession = async (sessionData) => {
    try{
      await axiosInstace.delete(API_PATHS.SESSION.DELETE(sessionData?._id));
      toast.success("Session Deleted Successfully");
      setOpenDeleteAlert({
        open: false,
        data: null,
      });
      fetchAllSessions();
    }catch(error){
      console.error("Error deleting session data:", error); 
    }
  };

  useEffect(() => {
    fetchAllSessions()
  }, [])

  return (
    <DashboardLayout>
      <div className="dashboard-container">
        <div className="dashboard-grid">
          {sessions?.map((data, index) => (
            <SummaryCard
              key={data?._id}
              colors={CARD_BG[index % CARD_BG.length]}
              role={data?.role || ""}
              topicsToFocus={data?.topicsToFocus || ""}
              experience={data?.experience || "-"}
              questions={data?.questions?.length || "-"}
              description={data?.description || ""}
              lastUpdated={data?.updatedAt ? moment(data.updatedAt).format("Do MMM YYYY") : ""}
              onSelect={() => navigate(`/interview-prep/${data?._id}`)}
              onDelete={() => setOpenDeleteAlert({ open: true, data})}
            />
          ))}
        </div>
        <button className="dashboard-add-btn" onClick={() => setOpenCreateModel(true)}>
          <LuPlus className="dashboard-btn-icon" />
          Add New  
        </button> 
      </div>

      <Modal isOpen={openCreateModel} onClose={() => { setOpenCreateModel(false); }} hideHeader>
        <div>
          <CreateSessionForm />
        </div>
      </Modal>
      <Modal
        isOpen={openDeleteAlert?.open}
        onClose={() => {
          setOpenDeleteAlert({open: false, data: null});
        }}
        title="Delete Alert"
      >
        <div className="dashboard-delete-wrapper">
          <DeleteAlertContent 
            content="Are you sure you want to delete this session detail?"
            onDelete={() => deleteSession(openDeleteAlert.data)}
          />
        </div>
      </Modal>
    </DashboardLayout>   
  )
}

export default Dashboard