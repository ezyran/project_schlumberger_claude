import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { Account } from '../Models/account';
import { Client } from '../Models/client';

@Injectable({
  providedIn: 'root'
})
export class ClientService {

  API_SignIn_Url: string = "https://projet-csc-backend.herokuapp.com/api/signin" as const;
  API_SignUp_Url: string = "https://projet-csc-backend.herokuapp.com/api/signup" as const;
  API_GetClient_Url : string = "https://projet-csc-backend.herokuapp.com/api/client/" as const;

  public authentifiedAccount?: Account;

  constructor(private httpClient: HttpClient) { }

  private httpOptions = {
    headers: new HttpHeaders({ 'Content-Type': 'application/x-www-form-urlencoded' })
  };

  public SignIn(pAccount: Account) : Observable<Account>
  {
    let data = `email=${pAccount.email}&passwordHash=${pAccount.passwordHash}`;
    return this.httpClient.post<Account>(this.API_SignIn_Url, data, this.httpOptions);
  }

  public SignUp(pAccount: Account, pClient: Client) : Observable<string>
  {
    let data = `email=${pAccount.email}&passwordHash=${pAccount.passwordHash}&name=${pClient.name}&surname=${pClient.surname}&phoneNumber=${pClient.phoneNumber}&streetNumber=${pClient.streetNumber}&streetName=${pClient.streetName}&city=${pClient.city}&zipcode=${pClient.zipcode}`
    return this.httpClient.post<string>(this.API_SignUp_Url, data, this.httpOptions); 
  }

  public GetClientFromId(pClientId: string) : Observable<Client>
  {
    let data = `${pClientId}`;
    return this.httpClient.get<Client>(this.API_GetClient_Url + data);
  }

}
