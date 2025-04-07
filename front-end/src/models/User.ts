export enum UserTypes {
    client = "client",
    admin = "admin"
}

export interface User{
    id:number;
    name:string;
    email:string;
}

export interface Types {
    id: number;
    type: string
}