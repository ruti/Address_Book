import { Component, OnInit, Input } from '@angular/core';
import { ContactService } from '../contact.service';
import { ActivatedRoute } from '@angular/router';
import { Contact } from './../contact';
import {Router} from '@angular/router';

@Component({
  selector: 'app-edit-contact',
  templateUrl: './edit-contact.component.html',
  styleUrls: ['./edit-contact.component.css']
})
export class EditContactComponent implements OnInit {

  @Input() contact: any;
  constructor(private contactService: ContactService, private route: ActivatedRoute, private router: Router) { }

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

  onSubmit(id: number) {
    console.log(id);
    this.contactService.updateContact(id, this.contact)
    .subscribe(data => {
        console.log(data);
        this.reloadData();
      }, 
      error => console.log(error));
      this.router.navigate([`./contacts/details/${id}`]);
  }

}
