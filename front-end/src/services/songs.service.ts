
export const GetAllSongsService = async () => {
    const response = await fetch(`http://localhost:8000/api/songs`,{
    });
    const data = await response.json();
    return data.songs
}

export const ApprovalAndCreateMusicService = async (formdata: FormData) => {
    const token = sessionStorage.getItem("token");
    const response = await fetch(`http://localhost:8000/api/songs`,{
        method : "POST",
        headers: {
            'Authorization': `Bearer ${token}`,
        },
        body: formdata
    });
    const data = await response.json();
    return {
        ...data,
        status: response.status
    }
}


