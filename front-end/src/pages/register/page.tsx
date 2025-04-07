import './page.css'
import { useForm } from 'react-hook-form'
import NavBar from '../../components/header/component'
import Input from '../../components/input/component'
import Button from '../../components/button/component'
import SimpleAlert from '../../components/alert/component'
import { useState } from 'react'
import { JSX } from '@emotion/react/jsx-runtime'
import { CreateUserService } from '../../services/users.service'
import { UserTypes } from '../../models/User'

interface NewCycleFormData {
    name: string
    email: string
    password: string
    confirm_password: string
}

export default function RegisterUser() {
    const [alertComponent, setAlertComponent] = useState<JSX.Element | null>(null);

    const { register, handleSubmit, watch } = useForm<NewCycleFormData>({
        defaultValues: {
            name: "",
            email: "",
            password: ""
        }
    })

    const handleCreateNewCycle = async (data: NewCycleFormData) => {

        if (data.password !== data.confirm_password) {
            setAlertComponent(
                <SimpleAlert
                    message="Confirme sua senha corretamente!"
                    severity='error'
                />
            )
            return null
        }


        const formdata = new FormData()
        formdata.append("name", data.name)
        formdata.append("email", data.email)
        formdata.append("password", data.password)
        formdata.append("type", UserTypes.client)
        

       
        const response = await CreateUserService(formdata)
        
        if (response) {
            if (response.status === 201) {
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
                return null
            }
        }
    }

    const formInputs =
        watch('name') &&
        watch('email') &&
        watch('password') &&
        watch('confirm_password')
    const isSubmitDisabled = !formInputs

    return (
        <>
            <NavBar />
            <section className='register-area'>
                <h2>Registrar Usuario</h2>
                {alertComponent}
                <form onSubmit={handleSubmit(handleCreateNewCycle)}>
                    <Input label="Nome" type="text" placeholder="Digite o seu nome" register={register("name")} required />
                    <Input label="Email" type="email" placeholder="Digite o seu Email" register={register("email")} required />
                    <Input label="Senha" type="password" placeholder="Digite sua Senha" register={register("password")} required />
                    <Input label="Confirme sua Senha" type="password" placeholder="Confirme a sua Senha" register={register("confirm_password")} required />
                    <Button name="Enviar" type="submit" variant="primary" disabled={isSubmitDisabled} />
                </form>
            </section>
        </>
    )
}


