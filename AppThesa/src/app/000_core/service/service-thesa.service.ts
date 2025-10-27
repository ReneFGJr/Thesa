import { environment } from './../../../environments/environment';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { catchError, map, Observable, throwError } from 'rxjs';


@Injectable({
  providedIn: 'root',
})
export class ServiceThesaService {
  http: any;
  //private url: string = 'https://ufrgs.br/thesa/api';
  //private url: string = 'http://thesa/api';
  private url: string = environment.apiUrl;

  httpOptions = {
    headers: new HttpHeaders({
      'Content-Type': 'application/json',
      Accept: 'application/json',
    }),
  };

  constructor(
    private HttpClient: HttpClient,
    private httpService: HttpClient,
  ) {}

  // Language i18n
  public getIPInfo(): Observable<any> {
    return this.httpService
      .get('https://ipapi.co/json/', this.httpOptions)
      .pipe(
        map((response) => response),
        catchError(() => throwError(() => 'Problem with IP info'))
      );
  }

  public getEditMode()
    {

      return environment.editMode
    }

  public api_post(type: string, dt: Array<any> | any) {
    let url = `${this.url}/${type}`;

    var formData: any = new FormData();

    for (const key in dt) {
      formData.append(key, dt[key]);
    }

    let apikey = localStorage.getItem('apikey');
    if (apikey) {
      formData.append('apikey', apikey);
    }
    /*
    console.log('API Post URL:', url);
    console.log('API Post Data:', dt);
    */


    return this.HttpClient.post<Array<any>>(url, formData).pipe(
      map((res) => res),
      catchError((error) => throwError(() => new Error('An error occurred')))
    );
  }

  public getId(id: number, type: string): Observable<Array<any>> {
    let url = `${this.url}/${type}/${id}`;
    console.log(`Buscador: ${url}`);

    return this.HttpClient.get<Array<any>>(url).pipe(
      map((res) => res),
      catchError((error) =>
        throwError(() => new Error('An error occurred while fetching data'))
      )
    );
  }
}
