import './App.css'
import TiaoPardinhoImage from "../public/tiao-carreiro-pardinho.png"
import NavBar from './components/header/component'
import Input from './components/input/component'
import Button from './components/button/component'
import { useForm } from 'react-hook-form'
import { useEffect, useState } from 'react'
import { User } from './models/User'
import { JSX } from '@emotion/react/jsx-runtime'
import SimpleAlert from './components/alert/component'
import { CreateRecomendationService } from './services/recomendation.service'
import { RecomendationStatus } from './models/Recomendation'
import { Songs } from './models/Songs'
import { GetAllSongsService } from './services/songs.service'
import ReportIcon from '@mui/icons-material/Report';

interface NewCycleFormData {
  youtube_url: string
}

function App() {

  const [token, setToken] = useState<string>()
  const [userLogged, setUserLogged] = useState<User>()
  const [alertComponent, setAlertComponent] = useState<JSX.Element | null>(null);
  const [songs, setSongs] = useState<Songs[]>([]);

  const fetchSongs = async () => {
    const response = await GetAllSongsService()
    setSongs(response.data)

  }

  useEffect(() => {
    fetchSongs()
  }, [])

  useEffect(() => {
    const sessionToken = sessionStorage.getItem("token")
    const userSession = sessionStorage.getItem("user")
    if (sessionToken && userSession) {
      setToken(sessionToken)
      setUserLogged(JSON.parse(userSession))
    }
  }, [])

  const { register, handleSubmit, watch } = useForm<NewCycleFormData>({
    defaultValues: {
      youtube_url: ""
    }
  })

  const handleCreateNewCycle = async (data: NewCycleFormData) => {
    if (!token) {
      setAlertComponent(
        <SimpleAlert
          message="Efetue o seu Login!"
          severity='info'
        />
      )
      return null
    }

    const formdata = new FormData()
    formdata.append("user_id", userLogged?.id.toString() || "")
    formdata.append("youtube_url", data.youtube_url)
    formdata.append("status", RecomendationStatus.PENDING)

    const response = await CreateRecomendationService(formdata)

    if (response) {
      if (response.status === 201) {
        setAlertComponent(
          <SimpleAlert
            message={response.message}
            severity='success'
          />
        )
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

  const youtube_url = watch('youtube_url')
  const isSubmitDisabled = !youtube_url

  return (
    <>
      <NavBar />
      <section className='main-menu'>
        <div className='menu-titles'>
          <h1>Ranking TOP 5 Musicas</h1>
          <h2>Tião Carreiro e Pardinho</h2>
        </div>
        <div className='main-image'>
          <img src={TiaoPardinhoImage} alt="" />
        </div>
      </section>
      <section className='songs-menu'>
        {alertComponent}
        <div className='recommend-music-area'>
          <form onSubmit={handleSubmit(handleCreateNewCycle)}>
            <Input label='Sugira uma Nova Musica' type='text' placeholder='Sugira uma Nova Musica' register={register("youtube_url")} />
            <Button type='submit' variant='primary' name='Enviar' disabled={isSubmitDisabled} />
          </form>
        </div>
        <div className='songs-container'>
          {songs && songs.length ? songs.map((music, index) => (
            <div key={music.id} className='song'>
              <div className='ranking-text'>
                <div className='ranking'>{index + 1}</div>
                <div className='text-content'>
                  <h2>{music.title}</h2>
                  <p>{parseFloat(music.visualizations).toLocaleString('pt-BR').replace(".", ",")} Visualizações</p>
                </div>
              </div>
              <img src={music.thumb} alt="" />
            </div>

          )) : <p><ReportIcon /> Nenhuma Musica Encontrada</p>}
        </div>
      </section>
    </>

  )
}

export default App
