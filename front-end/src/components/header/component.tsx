import "./component.css"
import LoginIcon from '@mui/icons-material/Login';
import LogoutIcon from '@mui/icons-material/Logout';
import logo from "../../../public/logo.jpg"
import { useEffect, useState } from "react";
import { Types, User, UserTypes } from "../../models/User";
import AdminPanelSettingsIcon from '@mui/icons-material/AdminPanelSettings';
import { logoutService } from "../../services/login.service";



export default function NavBar() {


    const [token, setToken] = useState<string | null>()
    const [userLogged, setUserLogged] = useState<User | null>()
    const [userLoggedType, setUserLoggedType] = useState<Types | null>()

    useEffect(() => {
        const sessionToken = sessionStorage.getItem("token")
        const userSession = sessionStorage.getItem("user")
        const userTypeSession = sessionStorage.getItem("type")
        if (sessionToken && userSession && userTypeSession) {
            setToken(sessionToken)
            setUserLogged(JSON.parse(userSession))
            setUserLoggedType(JSON.parse(userTypeSession))
        }else{
            setToken(null)
            setUserLogged(null)
            setUserLoggedType(null)
        }
    }, [])

    const handleLogout = () => {
        if (token && userLogged && userLoggedType) {
            logoutService(token, userLogged, userLoggedType)
            window.location.href = "/"
        }
    }


    return (
        <nav className="nav-area">
            <h2><img src={logo} alt="" /> Tião Carreiro<br />e Pardinho</h2>
            <div className="nav-links">
                {userLoggedType?.type === UserTypes.admin ? <a href="/admin" className="user-link"><AdminPanelSettingsIcon />Admin</a> : null}
                {token && userLogged ? <a onClick={handleLogout} className="user-link"><LogoutIcon /> {userLogged?.name}</a> :
                    <a href="/login" className="user-link"><LoginIcon /> Login</a>}
            </div>


        </nav>
    )
}