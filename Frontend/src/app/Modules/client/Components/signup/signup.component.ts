import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { Router } from '@angular/router';
import { ClientModule } from '../../client.module';
import { Client } from '../../Models/client';

@Component({
  selector: 'client-signup',
  templateUrl: './signup.component.html',
  styleUrls: ['./signup.component.css']
})
export class SignupComponent implements OnInit {

  signUpForm : FormGroup;

  constructor(private formBuilder: FormBuilder, private router: Router) { 
    this.signUpForm = this.InitFormGroup();
  }

  ngOnInit(): void { }

  public OnFormValidation() : void {
    console.log("Validation du formulaire ...");
    if (this.signUpForm.status === "VALID")
      console.log(this.GetClientFromForm());
  }

  private InitFormGroup() : FormGroup {
    return this.formBuilder.group({
      username: ['', Validators.required, Validators.minLength(3), Validators.maxLength(20)],
      password: ['', Validators.required],
      passwordConf: ['', Validators.required],
      email: ['', Validators.required, Validators.email],
      name: ['', Validators.required],
      surname: ['', Validators.required],
      telephone: ['', Validators.required],
      zipcode: ['', Validators.required],
      country: ['', Validators.required],      
      city: ['', Validators.required],
      address: ['', Validators.required]
    });
  }

  private GetClientFromForm() : Client
  {
    let client: Client = new Client();
    client.username = this.signUpForm.value["username"];
    client.email = this.signUpForm.value["email"];
    client.name = this.signUpForm.value["name"];
    client.surname = this.signUpForm.value["surname"];
    client.telephone = this.signUpForm.value["telephone"];
    client.zipcode = this.signUpForm.value["zipcode"];
    client.country = this.signUpForm.value["country"];
    client.city = this.signUpForm.value["city"];
    client.address = this.signUpForm.value["address"];
    return client;
  }

}
