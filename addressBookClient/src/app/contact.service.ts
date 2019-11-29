import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class ContactService {

  private baseUrl = 'http://127.0.0.1:8000/contacts';

  constructor(private http: HttpClient) { }

  getContact(id: number): Observable<Object> {
    return this.http.get(`${this.baseUrl}/find/${id}`);
  }

  createContact(contact: Object): Observable<Object> {
    return this.http.post(`${this.baseUrl}`, contact);
  }

  updateContact(id: number, contact: Object): Observable<Object> {
    return this.http.put(`${this.baseUrl}/update/${id}`, contact);
  }

  deleteContact(id: number): Observable<any> {
    return this.http.delete(`${this.baseUrl}/delete/${id}`, { responseType: 'text' });
  }

  getContactsList(): Observable<any> {
    return this.http.get(`${this.baseUrl}`);
  }

 /* search(name: string): Observable<any> {
    return this.http.post(`${this.baseUrl}`, name);
  }*/
}
