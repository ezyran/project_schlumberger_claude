import { Component, OnInit } from '@angular/core';
import { ClientService } from 'src/app/Modules/client/Services/client.service';

@Component({
  selector: 'app-navbar',
  templateUrl: './navbar.component.html',
  styleUrls: ['./navbar.component.css']
})
export class NavbarComponent implements OnInit {

  constructor(public mClientService: ClientService) { }

  ngOnInit(): void {
  }

}
