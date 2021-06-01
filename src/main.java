import java.math.BigInteger;
import java.util.Calendar;
import java.util.GregorianCalendar;
import java.util.Random;
import java.util.TimeZone;

class FirstApp {
    public static void main (String[] args){
        BigInteger key, b;
        var p = new BigInteger("72549841864194078899726837116892585496955575260843302046062234899404431414511");
        var g = new BigInteger("58759714624708089702199189818787533437405834872094654299502596364496752316901");
        var A = new BigInteger("71559523660568269188776671270040587101782920281998883402616283230525149718057");

        var B = new BigInteger("67719364237964147928448271440910028696447582883369228814004216914317779605772");

        var c1 = new GregorianCalendar(2015, 11, 13, 14, 40, 22);
        var c2 = new GregorianCalendar(2015, 12, 6, 15, 16, 36);
        c1.setTimeZone(TimeZone.getTimeZone("GMT"));
        c2.setTimeZone(TimeZone.getTimeZone("GMT"));

        long beg = c1.getTimeInMillis();
        long end = c2.getTimeInMillis();

        while (beg <= end) {
            b = new BigInteger(256, new Random(beg));
            if (B.compareTo(g.modPow(b, p)) == 0) {
                System.out.println("seed found: " + beg);
                key = A.modPow(b, p);
                System.out.println("key decimal : " + key);
                var res = key.toString(16);
                System.out.println("key hexa: " + res);
                break;
            }
            beg ++;
        }
    }
}