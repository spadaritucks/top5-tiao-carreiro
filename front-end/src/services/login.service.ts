import { Types, User } from "../models/User";

export const LoginService = async (formdata: FormData) => {
    const response = await fetch(`http://localhost:8000/api/login`,{
        method : "POST",
        body: formdata
    });
    
    const data = await response.json();
    return {
        ...data,
        status: response.status
    }
}

export const logoutService = (token : string, user : User, type: Types) => {
    if(token && user && type){
        sessionStorage.removeItem("token")
        sessionStorage.removeItem("user")
        sessionStorage.removeItem("type")
    }else{
        throw new Error("Erro ao realizar o logout")
    }
}