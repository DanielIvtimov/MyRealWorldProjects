import React from 'react'
import "./styles/CharAvatar.css";
import { getInitials } from '../../utils/helper';

const CharAvatar = ({fullName,}) => {
  return (
    <div className="char-avatar">
        {getInitials(fullName || "")}
    </div>
  )
}

export default CharAvatar