export enum RecomendationStatus {
    PENDING = "Pending",
    APPROVED = "Approved",
    DISAPPROVED = "disapproved"
}

export interface Recomendation {
    id: number;
    user_id: number;
    youtube_url : string
    status : RecomendationStatus
}