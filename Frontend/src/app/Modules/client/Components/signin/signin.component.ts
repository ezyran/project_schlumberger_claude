import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { Router } from '@angular/router';
import { Account } from '../../Models/account';
import { ClientService } from '../../Services/client.service';

@Component({
  selector: 'client-signin',
  templateUrl: './signin.component.html',
  styleUrls: ['./signin.component.css']
})
export class SigninComponent implements OnInit {

  signInForm: FormGroup;
  message?: string;

  constructor(private formBuilder: FormBuilder, private router: Router, private clientService: ClientService) {
    this.signInForm = this.InitFormGroup();
  }

  ngOnInit(): void { }

  public OnFormValidation(): void {
    if (this.signInForm.status === "VALID") {
      let account = this.GetAccountFromForm();
      this.clientService.SignIn(account).subscribe(
        (res) => {
          let account: Account = res;
          this.clientService.authentifiedAccount = account;
          this.router.navigateByUrl('/');
        }, 
        (error) => { console.log(error.error.msg); }
        );
    }
  }

  private InitFormGroup(): FormGroup {
    return this.formBuilder.group({
      email: ['', Validators.required, Validators.email],
      password: ['', Validators.required],
    });
  }

  private GetAccountFromForm(): Account {
    let account: Account = new Account();
    account.email = this.signInForm.value["email"];
    account.passwordHash = this.signInForm.value["password"];
    return account;
  }

}
