import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class PhoneNumberService {

  private baseUrl = 'http://127.0.0.1:8000/phoneNumbers';

  constructor(private http: HttpClient) { }

  addPhoneNumber(id: number, phoneNumber: string): Observable<Object> {
    return this.http.post(`${this.baseUrl}/add/${id}`, phoneNumber);
  }
}
