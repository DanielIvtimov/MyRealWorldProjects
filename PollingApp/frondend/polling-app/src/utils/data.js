import {LuLayoutDashboard, LuVote, LuPenTool, LuBadgeCheck, LuBookmark, LuLogOut} from "react-icons/lu"

export const SIDE_MENU_DATA = [
    {
        id: "01",
        label: "dashboard",
        icon: LuLayoutDashboard,
        path: "/dashboard",
    },
    {
        id: "02",
        label: "create poll",
        icon: LuVote,
        path: "/create-poll",
    },
    {
        id: "03",
        label: "my polls",
        icon: LuPenTool,
        path: "/my-polls",
    },
    {
        id: "04",
        label: "voted polls",
        icon: LuBadgeCheck,
        path: "/voted-polls",
    },
    {
        id: "05",
        label: "bookmarks",
        icon: LuBookmark,
        path: "/bookmarked-polls",
    },
    {
        id: "06",
        label: "logout",
        icon: LuLogOut,
        path: "logout"
    }
]

export const POLL_TYPE = [
    {id: "01",label: "Yes/No",value: "yes/no"},
    {id: "02",label: "Single Choice",value: "single-choice"},
    {id: "03",label: "Rating",value: "rating"},
    {id: "04",label: "Image Based",value: "image-based"},
    {id: "05",label: "Open Ended",value: "open-ended"}
]