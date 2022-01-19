import { Component, OnInit } from '@angular/core';
import { Account } from '../../Models/account';
import { Client } from '../../Models/client';
import { ClientService } from '../../Services/client.service';

@Component({
  selector: 'app-detail',
  templateUrl: './detail.component.html',
  styleUrls: ['./detail.component.css']
})
export class DetailComponent implements OnInit {

  mAccount!: Account;
  mClient!: Client;

  constructor(private clientService: ClientService) { }

  ngOnInit(): void {
    if (this.clientService.authentifiedAccount)
    {
      this.mAccount = this.clientService.authentifiedAccount;
      this.mClient = this.clientService.authentifiedAccount.client;
      console.log(this.mAccount);
      console.log(this.mClient);
    }
  }

}
