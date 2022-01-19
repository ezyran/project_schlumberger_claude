import { Client } from "./client";

export class Account
{   
    email: string;
    passwordHash: string;
    clientId: string;
    client: Client;

    constructor()
    {
        this.email = "";
        this.passwordHash = "";
        this.clientId = "";
        this.client = new Client();
    }
   
}