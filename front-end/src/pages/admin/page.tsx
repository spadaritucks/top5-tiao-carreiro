import { useEffect, useState } from 'react'
import NavBar from '../../components/header/component'
import './page.css'
import { User } from '../../models/User'
import { Recomendation, RecomendationStatus } from '../../models/Recomendation'
import { GetAllUsersService } from '../../services/users.service'
import { GetAllRecomendationsService } from '../../services/recomendation.service'
import ReportIcon from '@mui/icons-material/Report';
import ClearIcon from '@mui/icons-material/Clear';
import CheckCircleIcon from '@mui/icons-material/CheckCircle';
import PendingIcon from '@mui/icons-material/Pending';
import Button from '../../components/button/component'
import { ApprovalAndCreateMusicService } from '../../services/songs.service'
import { JSX } from '@emotion/react/jsx-runtime'
import SimpleAlert from '../../components/alert/component'


export default function AdminPanel() {

    const [users, setUsers] = useState<User[]>([])
    const [recomendations, setRecomendations] = useState<Recomendation[]>([])
    const [alertComponent, setAlertComponent] = useState<JSX.Element | null>(null);
   

    const fetchUsers = async () => {
        const response = await GetAllUsersService()
        setUsers(response)
    }

    const fetchRecomendation = async () => {
        const response = await GetAllRecomendationsService()
        setRecomendations(response)
    }

    useEffect(()=>{
        fetchUsers()
        fetchRecomendation()
    },[])

    const handleAprovarMusicaSubmit = async (id:number, status: string, approval : string) => {
       
        const formdata = new FormData()
        formdata.append("recomendation_id", id.toString())
        formdata.append("approval", approval)
        formdata.append('status', status)

        const response = await ApprovalAndCreateMusicService(formdata)

        if (response) {
            if (response.status === 201) {
                setAlertComponent(
                    <SimpleAlert
                        message={response.message}
                        severity='success'
                    />
                )
               location.reload();
            } else {
                setAlertComponent(
                    <SimpleAlert
                        message={response.message}
                        severity='error'
                    />
                )
                return null
            }
        }
    }

    return (
        <>
            <NavBar />
            <section className='admin-area'>
                <div className='users-list'>
                <h2>Usuarios</h2>
                    {users && users.length ? users.map((user, index) => {

                        return (
                            <div className='user' key={index}>
                                <p>Nome : {user.name}</p>
                                <p>Email : {user.email}</p>
                            </div>
                        )
                    }) : <p><ReportIcon/> Nenhum Usuario Encontrado</p>}
                </div>
                {alertComponent}
                <div className='recomendations-list'>
                    <h2>Recomendações</h2>
                    {recomendations && recomendations.length ? recomendations.map((recomendation, index) => {
                        const userRecomendation = users.find(user => user.id == recomendation.user_id)
                        return (
                            <div className='recomendation' key={index}>
                                {userRecomendation ? <p>Nome : {userRecomendation.name}</p> : null}
                                <p>URL : <a href={recomendation.youtube_url}>Link do Video</a></p>
                                {recomendation.status == RecomendationStatus.PENDING ? <p className='pending' > <PendingIcon/> Status : {recomendation.status}</p> : 
                                 recomendation.status == RecomendationStatus.APPROVED ? <p className='approved' > <CheckCircleIcon/> Status :{recomendation.status}</p> :
                                 recomendation.status == RecomendationStatus.DISAPPROVED ? <p className='disapproved' >Status : <ClearIcon/> {recomendation.status}</p> : null
                                }
                                {recomendation.status == RecomendationStatus.PENDING ? 
                                <Button name='Aprovar Musica' type='button' variant='primary' 
                                onClick={() => handleAprovarMusicaSubmit(recomendation.id, RecomendationStatus.APPROVED, "true")}  /> : null}
                                {recomendation.status == RecomendationStatus.PENDING ? <Button name='Reprovar Musica' type='button' variant='secondary'
                                  onClick={() => handleAprovarMusicaSubmit(recomendation.id, RecomendationStatus.DISAPPROVED, "false")} /> : null}
                            </div>
                        )
                    }) : <p><ReportIcon/> Nenhuma Recomendação Encontrada</p>}
                </div>
            </section>
        </>
    )
}