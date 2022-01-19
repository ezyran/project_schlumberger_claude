import { Injectable } from '@angular/core';
import {
  HttpRequest,
  HttpHandler,
  HttpEvent,
  HttpInterceptor,
  HttpResponse,
  HttpErrorResponse
} from '@angular/common/http';
import { Observable, of, tap } from 'rxjs';

@Injectable()
export class APIInterceptor implements HttpInterceptor {
  jwtToken: String = "";

  constructor() { }

  
  intercept(request: HttpRequest<any>, next: HttpHandler)
  {
    // if (this.jwtToken !== "") 
    // {
    //   request = request.clone({setHeaders: {Authorization: `Bearer ${this.jwtToken}` }});
    //   console.log('got JWT');
    // }

    return next.handle(request).pipe(tap( (evt: HttpEvent<any>) => {
        if (evt instanceof HttpResponse) 
        {
            let tab : Array<String>;
            let headerAuthorization = evt.headers.get("Authorization");
            if (headerAuthorization != null ) 
            {
                tab = headerAuthorization.split(/Bearer\s+(.*)$/i);
                if (tab.length > 1) 
                    this.jwtToken = tab[1];
            }
        }
    }));
  }
}