import { Contact } from './../contact';
import { Component, OnInit, Input } from '@angular/core';
import { ContactService } from '../contact.service';
import { EmailService } from '../email.service';
import { ActivatedRoute } from '@angular/router';
import { PhoneNumberService } from '../phone-number.service';
import {Router} from '@angular/router';

@Component({
  selector: 'app-contact-details',
  templateUrl: './contact-details.component.html',
  styleUrls: ['./contact-details.component.css']
})
export class ContactDetailsComponent implements OnInit {

  @Input() contact: any;
  hideAddEmail = true;
  hideAddPhoneNumber = true;
  email="";
  phoneNumber="";
  

  constructor(private contactService: ContactService, private route: ActivatedRoute, private emailService: EmailService, 
    private phoneNumberService: PhoneNumberService, private router: Router) { }

  ngOnInit() {
    this.reloadData();
  }

  reloadData() {
    this.route.paramMap.subscribe(params => {
      console.log(params.get('id'))
       this.contactService.getContact(parseInt(params.get('id'))).subscribe(c =>{
          console.log(c);
          this.contact = c;
      })   
      });
  }

  addEmail(){
    this.hideAddEmail=false;
  }

  addPhoneNumber(){
    this.hideAddPhoneNumber=false;
  }

  onEmailSubmit(id: number) {
    console.log(this.email);
    this.hideAddEmail = true;
    this.emailService.addEmail(id, this.email)
    .subscribe(data => {
        console.log(data);
        this.reloadData();
      }, 
      error => console.log(error));
  }

  onPhoneNumberSubmit(id: number) {
    console.log(this.email);
    this.hideAddPhoneNumber = true;
    this.phoneNumberService.addPhoneNumber(id, this.phoneNumber)
    .subscribe(data => {
        console.log(data);
        this.reloadData();
      }, 
      error => console.log(error));
  }

  editContact(id:number){
    this.router.navigate([`./contacts/edit/${id}`]);
  }



}
