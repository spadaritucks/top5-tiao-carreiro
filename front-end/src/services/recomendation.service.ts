export const GetAllRecomendationsService = async () => {
    const token = sessionStorage.getItem("token");
    const response = await fetch(`http://localhost:8000/api/recomendations`, {
        headers: {
            'Authorization': `Bearer ${token}`,
        }
    });
    const data = await response.json();
    
    return data.recomendations
}

export const CreateRecomendationService = async (formdata: FormData) => {
    const token = sessionStorage.getItem("token");
    const response = await fetch(`http://localhost:8000/api/recomendations`, {
        method: 'POST',
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