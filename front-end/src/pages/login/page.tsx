import './page.css'
import { useForm } from 'react-hook-form'
import { LoginService } from '../../services/login.service'
import NavBar from '../../components/header/component'
import Input from '../../components/input/component'
import Button from '../../components/button/component'
import SimpleAlert from '../../components/alert/component'
import { useState } from 'react'
import { JSX } from '@emotion/react/jsx-runtime'

interface NewCycleFormData {
    email: string
    password: string
}

export default function Login() {
    const [alertComponent, setAlertComponent] = useState<JSX.Element | null>(null);

    const { register, handleSubmit, watch } = useForm<NewCycleFormData>({
        defaultValues: {
            email: "",
            password: ""
        }
    })

    const handleCreateNewCycle = async (data: NewCycleFormData) => {
        const formdata = new FormData()
        formdata.append("email", data.email)
        formdata.append("password", data.password)

        const response = await LoginService(formdata)

        if (response) {
            if (response.status === 200) {
                sessionStorage.setItem("token", response.token)
                sessionStorage.setItem("user", JSON.stringify(response.user))
                sessionStorage.setItem("type", JSON.stringify(response.type) )
               
                setAlertComponent(
                    <SimpleAlert
                        message={response.message}
                        severity='success'
                    />

                )
                window.location.href = "/"
            } else {
                setAlertComponent(
                    <SimpleAlert
                        message={response.message}
                        severity='error'
                    />
                )
            }
        }
    }

    const formInputs = watch('email') && watch('password')
    const isSubmitDisabled = !formInputs

    return (
        <>
            <NavBar />
            <section className='login-area'>
                <h2>Login do Usuario</h2>
                {alertComponent}
                <form onSubmit={handleSubmit(handleCreateNewCycle)}>
                    <Input label="email" type="email" placeholder="Digite o seu Email" register={register("email")} required />
                    <Input label="password" type="password" placeholder="Digite sua Senha" register={register("password")} required />
                    <div className='submit-area'>
                        <Button name="Enviar" type="submit" variant="primary" disabled={isSubmitDisabled} />
                        <a href='/register'>Criar uma conta</a>
                    </div>

                </form>
            </section>
        </>
    )
}


