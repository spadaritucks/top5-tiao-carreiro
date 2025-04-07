
export const GetAllUsersService = async () => {
    const token = sessionStorage.getItem("token");
    const response = await fetch(`http://localhost:8000/api/users`,{
        headers: {
            'Authorization': `Bearer ${token}`,
        }
    });
    const data = await response.json();
    return data.users
}


export const CreateUserService = async (formdata: FormData) => {

    const response = await fetch(`http://localhost:8000/api/users`,{
        method : "POST",
        body: formdata
        
    });
    
    const data = await response.json();
    return {
        ...data,
        status: response.status
    }
}