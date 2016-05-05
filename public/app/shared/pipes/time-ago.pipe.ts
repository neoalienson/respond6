import {Pipe, PipeTransform} from '@angular/core';

@Pipe({name: 'timeAgo'})
export class TimeAgoPipe implements PipeTransform {
  transform(value:string, args:string[]) : any {

    // set locale
    if(args[0]) {
      moment.locale(args[0]);
    }
    else {
      moment.locale('en');
    }

    return moment(value).fromNow();

  }
}