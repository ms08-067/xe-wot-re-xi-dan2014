String.prototype.has = function(c) {
  return this.indexOf(c) > -1;
};
function jsmin(comment, input, level) {
  if(input === undefined) {
    input = comment;
    comment = '';
    level = 2;
  } 
  else if(level === undefined || level < 1 || level > 3) {
    level = 2;
  }
  if(comment.length > 0) {
    comment += '\n';
  }
  var 	a = '',
		b = '',
		EOF = -1,
		LETTERS = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz',
		DIGITS = '0123456789',
		ALNUM = LETTERS + DIGITS + '_$\\',
		theLookahead = EOF;
  function isAlphanum(c) {
    return c != EOF && (ALNUM.has(c) || c.charCodeAt(0) > 126);
  }
  /* getc(IC) -- return the next character. Watch out for lookahead. If the character is a control character, translate it to a space or linefeed.*/
  var iChar = 0, lInput = input.length;
  function getc() {
    var c = theLookahead;
    if(iChar == lInput) {
      return EOF;
    }
    theLookahead = EOF;
    if(c == EOF) {
      c = input.charAt(iChar);
      ++iChar;
    }
    if(c >= ' ' || c == '\n') {
      return c;
    }
    if(c == '\r') {
      return '\n';
    }
    return ' ';
  }
  function getcIC() {
    var c = theLookahead;
    if(iChar == lInput) {
      return EOF;
    }
    theLookahead = EOF;
    if(c == EOF) {
      c = input.charAt(iChar);
      ++iChar;
    }
    if(c >= ' ' || c == '\n' || c == '\r') {
      return c;
    }
    return ' ';
  }
  /* peek -- get the next character without getting it. */
  function peek() {
    theLookahead = getc();
    return theLookahead;
  }
  /* next -- get the next character, excluding comments. peek() is used to see if a '/' is followed by a '/' or '*'. */
  function next() {
    var c = getc();
    if(c == '/') {
      switch(peek()) {
        case '/':
          for(; ; ) {
            c = getc();
            if(c <= '\n') {
              return c;
            }
          }
          break;
        case '*':
          getc();
          if(peek() == '!') {
            getc();
            var d = '/*!';
            for(; ; ) {
              c = getcIC(); // let it know it's inside an important comment
              switch(c) {
                case '*':
                  if(peek() == '/') {
                    getc();
                    return d + '*/';
                  }
                  break;
                case EOF:
                  throw 'Error: Unterminated comment.';
                default:
                  //modern JS engines handle string concats much better than the //array+push+join hack.
                  d += c;
              }
            }
          } 
		  else {//unimportant comment
            for(; ; ) {
              switch(getc()) {
                case '*':
                  if(peek() == '/') {
                    getc();
                    return ' ';
                  }
                  break;
                case EOF:
                  throw 'Error: Unterminated comment.';
              }
            }
          }
          break;
        default:
          return c;
      }
    }
    return c;
  }
  /* action -- do something! What you do is determined by the argument:1   Output A. Copy B to A. Get the next B. 2 Copy B to A. Get the next B. (Delete A).3   Get the next B. (Delete B). action treats a string as a single character. Wow! action recognizes a regular expression if it is preceded by ( or , or =.  */
  function action(d) {
    var r = [];
    if(d == 1) {
      r.push(a);
    }
    if(d < 3) {
      a = b;
      if(a == '\'' || a == '"') {
        for(; ; ) {
          r.push(a);
          a = getc();
          if(a == b) {
            break;
          }
          if(a <= '\n') {
            throw 'Error: unterminated string literal: ' + a;
          }
          if(a == '\\') {
            r.push(a);
            a = getc();
          }
        }
      }
    }
    b = next();
    if(b == '/' && '(,=:[!&|'.has(a)) {
      r.push(a);
      r.push(b);
      for(; ; ) {
        a = getc();
        if(a == '/') {
          break;
        } 
		else if(a == '\\') {
          r.push(a);
          a = getc();
        } 
		else if(a <= '\n') {
          throw 'Error: unterminated Regular Expression literal';
        }
        r.push(a);
      }
      b = next();
    }
    return r.join('');
  }
  function m() {
    var r = [];
    a = '\n';
    r.push(action(3));
    while(a != EOF) {
      switch(a) {
        case ' ':
          if(isAlphanum(b)) {
            r.push(action(1));
          } 
		  else {
            r.push(action(2));
          }
          break;
        case '\n':
          switch(b) {
            case '{':
            case '[':
            case '(':
            case '+':
            case '-':
              r.push(action(1));
              break;
            case ' ':
              r.push(action(3));
              break;
            default:
              if(isAlphanum(b)) {
                r.push(action(1));
              } 
			  else {
                if(level == 1 && b != '\n') {
                  r.push(action(1));
                } 
				else {
                  r.push(action(2));
                }
              }
          }
          break;
        default:
          switch(b) {
            case ' ':
              if(isAlphanum(a)) {
                r.push(action(1));
                break;
              }
              r.push(action(3));
              break;
            case '\n':
              if(level == 1 && a != '\n') {
                r.push(action(1));
              } 
			  else {
                switch(a) {
                  case '}':
                  case ']':
                  case ')':
                  case '+':
                  case '-':
                  case '"':
                  case '\'':
                    if(level == 3) {
                      r.push(action(3));
                    } 
					else {
                      r.push(action(1));
                    }
                    break;
                  default:
                    if(isAlphanum(a)) {
                      r.push(action(1));
                    } 
					else {
                      r.push(action(3));
                    }
                }
              }
              break;
            default:
              r.push(action(1));
              break;
          }
      }
    }
    return r.join('');
  }
  jsmin.oldSize = input.length;
  ret = m(input);
  jsmin.newSize = ret.length;
  return comment + ret;
}