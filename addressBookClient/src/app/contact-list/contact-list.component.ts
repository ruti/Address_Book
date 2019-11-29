import { Observable } from "rxjs";
import { ContactService } from "./../contact.service";
import { Contact } from "./../contact";
import { Component, OnInit } from "@angular/core";
import {Router} from '@angular/router';

@Component({
  selector: 'app-contact-list',
  templateUrl: './contact-list.component.html',
  styleUrls: ['./contact-list.component.css']
})
export class ContactListComponent implements OnInit {

  contacts: Observable<Contact[]>;
  name="";

  constructor(private contactService: ContactService, private router: Router) {}

  ngOnInit() {
    this.reloadData();
  }

  reloadData() {
    this.contacts = this.contactService.getContactsList();
  }

  deleteContact(id: number) {
    this.contactService.deleteContact(id)
      .subscribe(
        data => {
          console.log(data);
          this.reloadData();
        },
        error => console.log(error));
  }

  viewDetails(id: number){
    this.router.navigate([`./contacts/details/${id}`]);
  }

  onSubmit(){

  }

}
