import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { Router } from '@angular/router';
import { ClientModule } from '../../client.module';
import { Account } from '../../Models/account';
import { Client } from '../../Models/client';
import { ClientService } from '../../Services/client.service';

@Component({
  selector: 'client-signup',
  templateUrl: './signup.component.html',
  styleUrls: ['./signup.component.css']
})
export class SignupComponent implements OnInit {

  signUpForm: FormGroup;
  message?: string;

  constructor(private formBuilder: FormBuilder, private router: Router, private clientService: ClientService) {
    this.signUpForm = this.InitFormGroup();
  }

  ngOnInit(): void { }

  public OnFormValidation(): void {
    if (this.signUpForm.status === "VALID") 
    {
      let account = this.GetAccountFromForm();
      let client = this.GetClientFromForm();

      this.clientService.SignUp(account, client).subscribe(
        (res) => this.router.navigateByUrl('/client/signin'), 
        (error) => { console.log(error.error.msg); }
      );
    }
  }

  private InitFormGroup(): FormGroup {
    return this.formBuilder.group({
      email: ['', Validators.required],
      password: ['', Validators.required],
      passwordConf: ['', Validators.required],
      name: ['', Validators.required],
      surname: ['', Validators.required],
      phoneNumber: ['', Validators.required],
      streetNumber: ['', Validators.required],
      streetName: ['', Validators.required],
      city: ['', Validators.required],
      zipcode: ['', Validators.required],
    });
  }

  private GetAccountFromForm(): Account {
    let account: Account = new Account();
    account.email = this.signUpForm.value["email"];
    account.passwordHash = this.signUpForm.value["password"];
    return account;
  }

  private GetClientFromForm(): Client {
    let client: Client = new Client();
    client.name = this.signUpForm.value["name"];
    client.surname = this.signUpForm.value["surname"];
    client.phoneNumber = this.signUpForm.value["phoneNumber"];
    client.streetNumber = this.signUpForm.value["streetNumber"];
    client.streetName = this.signUpForm.value["streetName"];
    client.city = this.signUpForm.value["city"];
    client.zipcode = this.signUpForm.value["zipcode"];
    return client;
  }

}
