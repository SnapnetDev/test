<?php

namespace App\Http\Controllers;

use App\Biometric;
use App\BiometricDevice;
use App\CommandLog;
use App\Company;
use App\Setting;
use App\Traits\Attendance as AttendanceTrait;
use App\Traits\BiometricTrait as BiometricTrait;
use App\Traits\FaceMatchTrait as FMT;
use App\User;
use App\UserFingerPrint;
use Artisan;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;


class BiometricController extends Controller
{
    use AttendanceTrait,BiometricTrait,FMT;
    public function data(){
        $received="FP PIN=1001\tFID=0\tSize=1396\tValid=1\tTMP=TVVTUzIxAAAEFhUECAUHCc7QAAAcF2kBAAAAgzsoZxYRAIEPQwDfAHUZVgAcAAAP1AA7FmIPVgBBAKsPeBZlAH0PQgC\/AFAZrgB9AKIP8wCBFk4PGQCGAJYPSxaJAOcPvwBNADEZPQCeAEgPcgCnFkAPpgCvAIoPkRa2AGgPYQB9AC4YhQC4AKYPrgDMFqsNvgDQAIIPaBbUAAQNrwAdAF0ZhADcAIYOmwDaFhEPtgDmAJIPhRbtAIgPwwA0AFYZOwDyAB8OgwD5FpwOSAAOAcoOQBYVAZkOlwDgAWgYMgAoAZIPrAAtF\/0OgAAqAUQNhhYxAewNGQDyAYwYiQA\/AfAOvABBF30NQQBNAU4PZZZ3EUsVFXHD8ToRYIAFkcKVwvcn4VoT1nHC\/xrtpwcW3mubjQSIbXIb+G0WYidV0PwxfGgLLQdGD+cDOYMcmvmj5ZVc96P+8PsVYwL1bPUJGfuioekZEW+pkdIIGQYqgYFPKF6T2EBG\/F590auFlyyfpfCJ8rP0PEnk9ImDVQTQFEoeRACFh5bcnAxBfYOK2QBFBDwSwRKbhIp6zfwT+O4agISSEJaJRIAlhigGyHotdOfwxJis\/kULje5Egm5qzPtaDMqEQH6VBbOANJYxkkSGTHjE\/OVy5vjK\/NvkQAQBdEFyMIzRhND\/qvqLBB6SJz6RvIogLwHHEx89BABFAHrDmgkEhAAMPf\/+ZMMAqxcR\/0AEAFbD\/fvoCgCGBwz\/g0ZFEwFOCnp7DsVnFBYu\/v9H\/sD4BQQpGXeEEQBc2QDEPf7\/Pv5XKsAARQhq\/3EJAFGHcHrV\/oQXAAhSJ8H66UD9wMD9wP3A+looFAB6X4ZSf5fphsOXwRgAzWHaUMH9MP87Rzho+lMGAIBlEP87wBk0AXhnfZfGOsT61pbDwMXCxUzCxtWHw8PBxcIFxRsWr3midYjEsHVY18DB\/8XBw0P\/jwoBA3rT\/v4H\/UXrRf7\/\/v\/+Bf\/F6P\/\/wMD9wc8AO5ZI\/nCOwRTFvYe\/cIJ5wsJntsEHFhmKVsEMAPGKVH7FhsOCCgAGijTp\/1ReCAC9TjrGJGEUADujSbzDxdfCwqfCwcEEw4EGAXakTMnJDcjC1MLFyMPEwgbCCxa7pzr\/\/UEF\/T\/oKQgApbNJ7\/4cHQGGtb3GwgvHwNPDycYDAAEROvsCAXDUA6rCBK7G1cTCxMTCwQfCDha\/10b\/Kf0FJg4WrttW\/sD+O\/77NAIAX+Iexc4Asf5SMzAjBQB681QlEwDE9En+Ov37UP3B\/v1iVc8QFRwbasCDwgTVBQkIiQYQxA9T7P4NBggsDFXAwGcJFKQ3af\/+ZTDAEA8oAf86DBCsg23768NHMsEDEN9JAukCEIpbesPBECRJAmVSQgALhgEEFgpFUgA=\nFP PIN=2686\tFID=7\tSize=1164\tValid=1\tTMP=SitTUzIxAAADaGoECAUHCc7QAAAbaWkBAAAAg5UadWg8AAsPmACZABRnWQB5AGgP6QCVaFkPeQCnAKkPtmipAJkPkgAJAHtmtgDiADwPJwDmaDQPHgDpAIMPb2jrADoOhAApACdlsADuAJ8PdQD+aLEOfQD+ANgOQmgAATkPkgDEAZBm2QADAUcPBwATaVsPrAAYAUcPaWgpARwPrQDrAXdniwA6AZkPUgA\/ae4PowBMAbsPZGhaAZkPl\/QOp7TLCgzbs7uvRhFAlu5fE\/Ez77YXPZ++BhfXSyHiX2hHW1UiP7IPXZ+S59\/HmvDO+wKZVWc+DwPzSRqsH13DrAnh7oWScWcmmE8NlRDxW9wzNpFojQnn4vfyAGh2dHmVcurkYO8JfMOamdoRFwyvPnrwKsXXeoXyAHREzPZ1h1r4Ka8BdH99GFu5cbx\/qvmf5NIMywBufdDGQ7gcASApxAINdZoFALEAFpYKA\/gABitKPRHFOxOSw\/8rQUdXOxQDSyrtS8H\/\/Y5GUJc+DAB7Pgk6RkIYFwAUQevAh\/44qEFUwP3BS8gAlzKRwobBfMKQCAP0WxD+PnAJxZtjf0fAwmUXAMRk3S78wf\/AwP85RkyoRcAWAAhqJf\/Dlv9r\/v7\/\/ocrUX0BB3jkWzLxR8OWQXcLAFR6ro39qf6QFgAHlxvAT5Y6\/\/44N\/8FewZoK5paXBUAdqaZEI\/EwYt4wgTAYmEBdKhxl8FBGQNqttPA\/kz\/7igzLGtbDwCOx0XGwarCopbDxsLfAAakx\/zBMsD+O\/78lvzC\/f\/\/\/wX\/wajBwP4EAJYLFv2VCAB82VOhB8KpbAGP4hcwBMW55F\/8\/QUAg+gG8hhrEZ8Cmv8ExeTrXC4cAPfnsFZHauzDoMKB\/4uaBAN17kZpBgCCNTrEqsfICgBs\/PTBwcnDxsIEEIDGIqNbET4FQInCTsXDq3aDwsPBwwXAx6jF\/sHBwcEHwsGtwMHD\/8LBBMHDqcPCwsHCC9VEBlxwwsHDwKLjEPBuvMDHUlxpR8HGqYVvwMHAwQfClanCWxQQxAsMn5Oqw8OplsKRwhDaY0guOQUQw9ZJ\/0QpEGosJMIAwcOuicN5wcR1BY3GqMXBwcPAxAXBw6vAwsLEwQfV1z4y\/\/4vAxA7lhrBOkMAC0MBAMULRjo=\nFP PIN=2686\tFID=8\tSize=1548\tValid=1\tTMP=TctTUzIxAAAEiIwECAUHCc7QAAAciWkBAAAAhLUtaYhEAHcPTwCDAPyHjABKAAcPAABOiIAPuABPAMQPvohZAIwPRACfAPeHpABzAA4PpwB5iGkP9wCFAFMPyoiYABkOWwBeAPeHNgCeAOIPxwGjiCMP5wCqAFwOiYi1AHkPogB4ABeGJwDNANoPTgDLiG0OnwDRANAO4IjQACoPigAaAGeGkQDrAAIOvwDpiOMNggD5AI0NdIj+AEYN1wA6AKqGnAAMATQNUwAXiSgNOAAXAfkPm4gdAa0NUADaATOH6gAfAUAPaQAkia0NXwAjAfUOtogvAaUOdQD2AbeGmAA0AbEOSAA\/ia0PvgA\/ARUORIhCAbcPlQCOAQ2HrgBOAYsOfQBSiX8PbABZAW8PjggTcHeE3QZPgLsbA5B+C4d8tIBZfV51uHZxgBP2qAbQDVKBYf6+fLcET4VGDh8Ip3S7\/H6KHnU+\/p58hghWhHP6WozPEaf2n\/HvCYd+HIsWBbr55XuCgOtyHeJMDzJltQE4ntEjdvmL69+jnAgd0dj38akxBRC2HhqifDMJTGMk+IUJLaPhvf0iDJzxFYCB4WitBdxJKYkQZSZG\/nJahD+OrQ+JiHGK\/fd892x\/UQZSDruHcA\/gAJF4VQgc\/poK65arcrefpAABgHh63fhpf+PuiXAE1VH33Qqsh9aNOAQZqpkGBPwFLWD6WS0RRThWmgqri4J\/JV2EWnkLFQ\/VvXmHZffJOpN1ovrKo38DpIoBUIAaIC7EAkyXBgQAmhGGBsAViNMmj3zAdbd0NJgB2DWTfnYGZ8VIZQ8AVET0hsAtwj0LAGlGdAWAxEjBw\/8UAOmDkMTjhn\/Ce2rCzwDEwYf\/wMZCW8EAlsIH\/\/8GAL2KCTx2BAB9Uvr8kgwE\/VJ3c4lrw80AutERwTVKCwC7WgS3wyhSDQB2nnfFSXlwwXcKAGxxAnZD\/sBhDwBkcoIIwf+iwcDBoQsEIHYM\/lb+\/zpVAIhffml4FgA2h5ftw8L\/fsGABHjEDhEAYJnwO\/cyxLbBEgB8tXQEw4TzwcLDwcNp0gAtQdvAwP5M\/jspRXfDwP1aDQBDznQfw8DBxWbBygCnWRIz\/\/7\/wO39+UgGAObUJ0SGHQWH06drecD\/qpDASMDAwMP\/wQb+xtUIAJToAPw4IyCeAc3sp8PABsJ5S8PCwsDAwgTCxkmEBQB\/\/EZJHRWEAatcwv\/CnMHFCsB6w\/9nwgWYB5ieATDABRBTD8189PwKELgdBcXGTsnIlRgQ0dC6lknFwMTDxcEGwMdJxcHAwsHDBsISmJUXNMLFyA3Ex0vCwsbGxcQGwsVMwsHIHhAd3cY6d0L+\/\/vC\/jj\/+nfB\/\/\/\/\/v86wft3cwQQOBw9vAUUXCFAJwUQUuY9xgAFEOsjPf87CRTpJjFpoMId1fU0MnHBwMHCfAf\/xkqTwn7Cao\/REMa33cXBxHWhA8HEQP7DwaADEOpBKUkCEJdPE8bmEMjs5cOd\/4nCBcPES8F9l8LBwgXAxUzBwMLCwcKXQgSDQgEAAAtFlwA=\nFP PIN=2686\tFID=9\tSize=984\tValid=1\tTMP=S6FTUzIxAAAC4uUECAUHCc7QAAAa42kBAAAAgg8TKuIcAOQPRgChAOTtKABsANsPTABu4nYPaQB9ADcPGOKCAEgPnQBYAILtxgCrACEPSQCw4koOegDBABcPYeLJADsP1gAjAJ3tjwDqACIPugDw4iYPJgAHAfsPPuIMATQPrwD2AZnsrgBNAYcNZwBN45EPzwPfCyafEO639wOi\/Zdb9LEJd4U7+Y+Tu30o7aZ3EW8jZvJbX9UPY2Ox7qMa25VlmHoRz6MQVIoneN6wOWoz8KoVmWFifzcHzfzKUPkIRAQXVAPtiA4h5Tob2fo\/D+b7J\/U7DJd7dPt876WB6QvC9KNr5yECwiQBAd0bbMEAT+NoawoAYgG0wsK+wYMNAIgERsOJIsP+g8EEANkTYh3EEACiFJAGwcGewWqLwAQA4BZYshkAohyMk0bAwyLDwsDBwcAFwsIiwYUQALsvSnXDbv7CfI0OAHo7kWHAkIR8HAB7RJKVwMHDwcCLBMJ9gMPAUXILAAdOjr6KxGoMANCnl4mTwsGIEQBCrVySIP+Halv\/F8WGaZihiMDCam+0hsLDAc9rl4tsBMN+IML\/wnRqwDrBwiCRlQoAbHo1\/v0e\/sDAMSYAEnueIcBaw\/\/FwQeAgSLDUnh4wcEHw2Ug\/ggAFoZMtWIh4teGnJD\/wQTDoiPAkHbCWcG9wZFyCAAdh0ZltcEL4pubg8LFyASDH+LcpZzBwMG9xMImwpJ3Z8DCBcLCIF0EAMquIIcHAnKxCSdvDQBNsVFCw8HBwYLCyQCJVEfCw8HEcQdcH+LiuJ7Bwf8EhMEhxcPDwXDBRcHCI8DAw\/8NALy+yx75\/vwrQ\/\/CAJkpJoB7CwBiCjfClYSFGgDn5G50cyPDwcOywcMFwMOcwlUIAJDu4oiG5QHd8pdeYMwAkCY7xMJ1fgjFg\/bFwMH\/w5EJxX73z4rAwZ4KEPgRNSB9wcP+xFKHAAmhAAAAC0VS\nFP PIN=2686\tFID=0\tSize=1108\tValid=1\tTMP=Sn1TUzIxAAADPj0ECAUHCc7QAAAbP2kBAAAAg+MVUT4WAPIPLwDgAGcwQwAmAGYO9gA1PukOpQA7ALoPbT4+APsPtgC5AIgxywB+AAsOHwCDPokOXwCSAD8PsT62AJIPmwB5ABUxJQDEAO8PjgDXPvgPZADfANUPsj7oADIPdgAuACIxbQD9AHYM2QD8PmUPXgAZAaQOjz4eAVoN8Y7HkvExVHwhAQ52WAX+SHiCUYR5hr\/7gLg69y93LwjalYRA+IH6BTv7uIT6v8Z7fXx+\/Zb71D2i58fvZX72YbA1YIab5cf3Z\/vYsaLjmesaD1rYiswAG6acE6E6HcwttK35EvcoJFXBVl8U43f\/b+oH9dImCPdBr\/gO5NQEjYAMIC4BxyEfjwkAjgb6LfoEA7gJd3wFAGLOdMLFwwQASx2GXA0DaR\/0wTb9wIE4Bz4\/JG12EQDyOO7+Skc2Qz8ExaA5Q1gRAPZIlwXD\/P5\/wcJqawTFI0tcZRMAIlPtiVn9wSjCNUkTAOVf5MP+XT\/BwP06Q0AxAbZojINyOoTDSwYAvmwQPQb8Fz4Rcev+w\/06wMP+P8H\/\/f7AB\/3DMAGyeonAgwXC\/FdzEADGfJCrwnNcwMFuFQAPReRDAFxAwPzCNwUFA\/WDEMA4DwCnkvQGKGj\/\/mEWxQuu2v5LWEv+wAX+\/P49\/wwArrRVwMH8wHrAwMAWxQ2\/6f3AQMDC\/f0vRX8NAJ6+FsE4NMLD\/y8XABS\/J\/87BsD+Mf\/C\/QX\/\/ML\/wBMATtQyQf8N\/\/3+\/f89gxgDP9nrwsDB\/JY3\/cP+\/v\/C\/\/7rwBU+Ct7kOFj\/9Ef8wP8r\/g0AaRoM\/f7\/+vz+\/\/87\/8MxAa7knmySBH7CAxsADuriOAXA\/MD+\/v\/9\/lc4\/\/zCwf7+\/8EKxbXpFyv9\/\/5TC8Ww7wo1Pv5KDAC\/7RnD+\/36wcDABP38KAEf++D\/wDv7wv\/9\/f7B+\/05\/\/wLSwcAaPyAA8FvNRF\/AKfBwwDDx\/z+jQkQewVlwcf5wmYIEIITqMX8af8HEGwDcQZixzsRWRliwJjAEGoiWz0EEF4dn0AELosjWlRGA9X4JCTBAxD6NB4FUkE+CkMBAAALgFI=\nFP PIN=2686\tFID=1\tSize=1304\tValid=1\tTMP=SpFTUzIxAAAD0tIECAUHCc7QAAAb02kBAAAAg38ectIWAPoPdADzAHDdFgBBAOQOHQBJ0hcPVwBRADYP1NJVAJIPXwCiAHPdYwCGAPYPaACR0hkPjwC6ANAPH9LCAOIP3AAAAKHdcwDLAAAPuADb0hkPcwDpAOEPxNLqADIPSwA0AOjdtgD5ALEPpwD\/0lsPMwABAR0OOdIGATkOXQDMAWHdqwAUAUIPsAAW01QPjgAVAX8PftIcAVAPmgDaAU\/dWQAgAYwP+QA605YPpwBVAR0PxVraC9OPyni3glzXqvOzd5tzdYfA0fanwYZyggL+t61Kf38jwX6LfOAvS4hDBq\/jAgWIqUKL1hXC\/YL9vCnSD8+rNnP+84BdpOjC3G7tyBi+J0MFMQ25ztskslMnk6vxqZI0t\/7FsIEicCtksPgG6LhyxKKR73d757AgU0rZ7Qis2jLdpPm9ixKQiQcuTMD35XA1bYxm0y0gmTUH0ZTYDbotvC7mOPI9Cw6os6bCY45rm54j2ZuvECA0AQKNHobUAYkBfcL\/RgMDaAAM\/gsAdtP6w+nB\/0cQANndkHIThcDAcHAFxSUmhfyfCQBwM7\/D\/BLAbAgAeDU4RP2QCAByOnBlosIX0vM6kP6HwQR7dhJoeAUA3EjWwEXGARxL6UteOsFFksBRCgBaTDVB\/JPABgDaThwEVwTSUlJ0b2oQxVtQJcFG\/sD\/\/wT+UiwJAFtkdHsEjg3SYmX3RDX\/\/cH82gFca3DBU0gOA7eA9EE4\/cCQ\/gnSX4dpWsHAUQ4DtIn3Pf5B\/ztoFNIGjdr+\/sHwRf2GQf9UwQ0AbJCKVnTBZMMJAHSRE\/n+wP\/+BgBqlhmd\/hYAF6ni+1b+lSr+Pl4YAMyr3+LBwFP+\/8A6\/cIuwP\/\/wMBGywCQaBEq\/\/x7\/eoTAwrFoMDBwJEEg8GO\/o4HAHfJxf\/4Lfz9BADgyeEsDdKD2RD8\/f0E+8D1\/jAFAHbn1fv4LQcAbup3mwYRA5\/s6f7\/\/fo7\/P4s\/8D\/\/\/79Ow0Dp+we+sD+\/TrA+PjBBwDI8C32LxvSE\/PQ\/v\/+BcBF5yZGSz0PAHz1uRHDk8XEwME6nQfSZPn98v0ExV3\/u4gIALj9Ogf8\/C0rFhA3ANwE\/jIs\/CEywfv\/Oz4Jwm0ASS77+T4dGsLpB7TDWsFBwsAQwYnBwMCLAsAFwnAYV\/\/\/\/D8HE6UZT\/\/+\/SDCEKjJQf4xwQgQuSFMEv39KgcQmuJM\/ZL8HRDYM9NnlPwQwsDCxcDCBMHBEsPAwcDHwASpAMK3VHDEUkLFC0DTAQALRVIA\nFP PIN=2686\tFID=2\tSize=1572\tValid=1\tTMP=TdlTUzIxAAAEmpsECAUHCc7QAAAcm2kBAAAAhEcxz5oeAAAPYADnAOiVJgAsAF0PEgBFmhEPJABTACgPHJpfAN0OmgCzAPqVYwB7AGsP8ACImugPGQCQACQOGpqWAGQOSABmAO2VegClAPgPcACpmhgP6QCtAOEPtJqyAIEPmAByAAOVOgDHAOgPnwDUmu4P2wDVAOkPaZrYAPgPjQAcABKVfgDzACEOngDxmmQOowD7APAO9Zr6ALAPMwA5AH6UTgD+AOUNQwD7mpIOAwH\/AHAPYJoBAfcNgwDCATiUSwAJAXANEgAOmzwPWAALAagOSJoQAe4NmADQAaiXAQEYAa4OyQEbm6oOwgAhAXsN8poiARgPbgD1AWyXfQA1Ac4OQwAzm2kOkABAAZoOXJpAAeoOvgCAAe6XagBJAekOcwBKm+0NtvHXB8YXCwn6A9eDanK2gRvpJxHXF6vvMBBeCMoHhfDn\/baBBwzaCrPnb4ROhEfuBIYRC74AAX6dYWIFxIYJfu9\/OprnAiaHovHyjA+LxZraFGv1b\/vbl9qm3Gp5fpptcRBv8BIRpvivAq9rpPkeCNKN5wuG5ZrxHQmy2E\/kxm7vIDoRiZBc5TKWQHCBgGH6WIvBPb\/52f0+dDsEGZUIlyaM7HdEgMHiPVqZdBHrkAUVkAMMLZTtjMgVEDD8HV2SSIYljxGeL4yigGImRYOdZryISIKAg5wJaeike3kb6QT0mJ1gbPxNlor4Z4AOT08XSWxhbv9mLQYkAl2CvGuEaM3ooZ84C6UCBfuxCLf6qQF9h08e8JlfLR+MKQGYgnGFcQcmjmMwhCwMGmK5ACA0AccpGakIAGcBa8A6wsRnpAwAeAFtrmJ7Ww8AiwFwfoPCxVvAiAYApAG0cfqIAd8Fff\/BRv9Z8cGIEADgDrh0xVpjW8BzBAAKG\/61EADHH3diBP90ZF2WAwAdIafDB5rOIgbCBQDmLl5liRYBEkGMOMLGWGd0XFNvxMMA2NgRRf4YARSUk8RawIlyacD\/mcHGWowWARRumgdRxF52wHDBRYXOAJrr+zP\/NlkHxZZy7YFZCwCeecXA+q\/+YAkAX3yowVL1FwEVfZrAr4aP8FfBexQAOU\/nxGT+ZMD\/MzulwBObFZmewMFnRYn7WP\/F\/cLAwI8OBOWi9zvAKkaBEgTWpOvBSzX\/BSjFrwkARKdkagQ9CZqxqomJi2BfCwQiqhApwDBMwwDoKiNL\/wwArHSDh1j+dlwEALd0GjyWAZy3Bv4niVwTmwS\/pMJqgLmJ+1rDVsPAGACFxOPJ\/sP7\/sD+O\/\/EZPzAwP\/+wj7AxZcBXs7wwMDhwPmrwAsAcNf6BcD\/Zv87\/gQA3xIk+mUJAJPZEPzrQgKa2NkrVcADxYPxhv4XACr39AbBxWb+V\/z9\/v7w\/vll\/zUTEDUDNcLFWvwq\/P3A\/Dn\/NGQHEH8Fg8UGKQCKhgYnHQ0QiwjvWcH5wPz6\/Tn\/+2YHEHkLZ2g7AxRPDED\/BhB50FrEZk0FEH4dUAUdAIr4Jx7B\/gXVvC\/N\/jwEEPg051cHio1CZP1SQsULR5sBAAtFUgA=\n";
        $array = preg_split('/\\r\\n|\\r|,|\\n/', $received);
        foreach ($array as $a) {
            $totalwords = strlen($a);
            if ($totalwords > 10) {    //if all the required data is inside the received format
                $data = explode("\t", $a);
                $pos = strpos($data[0], 'FP');
                if ($pos !== false) {   //Fingerprint Log
                    $emp_num=str_replace("FP PIN=","",$data[0]);
                    $finger_no=str_replace("FID=","",$data[1]);
                    $size=str_replace("Size=","",$data[2]);
                    $valid=str_replace("Valid=","",$data[3]);
                    $finger_print=str_replace("TMP=","",$data[4]);
                    $formatted=['emp_num'=>$emp_num,'finger_no'=>$finger_no,'size'=>$size,'valid'=>$valid,'finger_print'=>$finger_print];
                    $this->fingerPrintLog($formatted);
                    return $this->returnOk();
                }
                $pos = strpos($data[0], 'USER');
                if ($pos !== false) {   //User Log

                }
            }
        }

        return 'done';

    }

    public function softClockIn(Request $request){
        $long=$request->long;
        $lat=$request->lat;
        $company_long=Setting::where('name','company_long')->where('company_id',companyId())->first()->value;
        $company_lat=Setting::where('name','company_lat')->where('company_id',companyId())->first()->value;
        $enforce_geofence=Setting::where('name','enforce_geofence')->where('company_id',companyId())->first()->value;
        $distance=Setting::where('name','distance')->where('company_id',companyId())->first()->value;
        if ($enforce_geofence==1){
            $cood_distance=$this->getDistanceFromLatLngInKm(['latitude'=>$company_lat,'longitude'=>$company_long],
                ['latitude'=>$lat,'longitude'=>$long]);
            if ($cood_distance>$distance){
                return 'Not within Company Geofence';
            }
        }
        $user=User::find(Auth::id());
        $now=Carbon::now()->format('Y-m-d H:i:s');
        $data = ['emp_num' => $user->emp_num, 'time' => $now, 'status_id' => 0, 'verify_id' => 1,'serial'=>00,'long'=>$long,'lat'=>$lat];
        $this->saveAttendance($data);
        return 'Successfully Clocked In!';
    }
    public function softClockOut(Request $request){
        $long=$request->long;
        $lat=$request->lat;
        $company_long=Setting::where('name','company_long')->where('company_id',companyId())->first()->value;
        $company_lat=Setting::where('name','company_lat')->where('company_id',companyId())->first()->value;
        $enforce_geofence=Setting::where('name','enforce_geofence')->where('company_id',companyId())->first()->value;
        $distance=Setting::where('name','distance')->where('company_id',companyId())->first()->value;
        if ($enforce_geofence==1){
            $cood_distance=$this->getDistanceFromLatLngInKm(['latitude'=>$company_lat,'longitude'=>$company_long],
                ['latitude'=>$lat,'longitude'=>$long]);
            if ($cood_distance>$distance){
                return 'Not within Company Geofence';
            }
        }
        $user=User::find(Auth::id());
        $now=Carbon::now()->format('Y-m-d H:i:s');
        $data = ['emp_num' => $user->emp_num, 'time' => $now, 'status_id' => 1, 'verify_id' => 1,'serial'=>00,'long'=>$long,'lat'=>$lat];
        $this->saveAttendance($data);
        return 'Successfully Clocked Out!';
    }
    public function enrollUsers(){
         $company_id = companyId();
        if (isset(Company::find($company_id)->biometric_serial)){
            $users=User::where('company_id',$company_id)->whereIn('status',[0,1])->get();
            $this->createMultipleUsers($users);
        }
        //add all users to all other biometric devices
        $biometric_devices=BiometricDevice::where('company_id',$company_id)->get();
        foreach ($biometric_devices as $biometric_device) {
            $this->addUsersToDevice($users,$biometric_device->biometric_serial);
        }
        return Redirect::back();
    }
    public function removeUsers(){
        $company_id = companyId();
        if (isset(Company::find($company_id)->biometric_serial)){
            $users=User::where('company_id',$company_id)->get();
            $this->deleteMultipleUsers($users);
            $biometric_devices=BiometricDevice::where('company_id',$company_id)->get();
            foreach ($biometric_devices as $biometric_device) {
                $this->deleteUsersFromDevice($users,$biometric_device->biometric_serial);
            }
        }
        return Redirect::back();
    }

    public function checkDevice(Request $request)
    {
        $this->savetoTable($request);
        $last = Biometric::orderBy('id', 'ASC')->first();
        if ($last) {
            $time = $last->created_at->timestamp;
        } else {
            $time = now()->timestamp;
        }
        //$contents="C:18:DATA USER PIN=22\tName=Soladoye\tPasswd=1234\tCard=123456\tPri=0";
        $log= CommandLog::where('biometric_serial',$request->SN)->orderBy('id','desc')->first();
        if ($log){
            $command=$log->command;
        }
        $command = "GET OPTION FROM:%s{$request->SN}\nStamp=1565089939\nOpStamp=1565089939\nErrorDelay=30\nDelay=10\nTransTimes=00:00;14:05\nTransInterval=1\nTransFlag=1111000000\nTimeZone=1\nRealtime=1\nEncrypt=0\n";
        return $this->commandresponse($command);
    }

    public function getRequest(Request $request)
    {
        //this is the second thing that gets called on device start up. You send OK if it you dont have any command to send
        $serial_number=$request->SN;
        return $this->commandToSend($serial_number);
    }

    //once a successful command request is made, the device makes a call to deviceCMD
    public function deviceCMD(Request $request)
    {
        $data=$request->getContent();
        $arr = preg_split('/\\r\\n|\\r|,|\\n/', $data);
        $return_data=[];
        foreach ($arr as $a) {
            $totalwords = strlen($a);
            if ($totalwords > 10) {    //if all the required data is inside the received format
                $d = explode("&", $a);
                $id = $d[0];
                $status=$d[1];
                $id_length=strlen($id);
                $position=strpos($id,'ID=');
                $end=$id_length-$position;
                $real_id=substr($id, $position+3, $end);

                $status_length=strlen($status);
                $position2=strpos($status,'Return=');
                $end2=$status_length-$position2;
                $real_status=substr($status, $position2+7, $end2);
                $return_data[] = ['id' => $real_id, 'status' => $real_status];
            }
        }
        $this->updateLogOnReturn($return_data);
        $this->savetoTable($request);
        return $this->returnOk();
    }
    public function receiveRecords(Request $request)
    {
        if (isset($request->table)) {
            $table = $request->table;
        } else {
            $this->doNothing();
        }
        switch ($table) {
            case 'ATTLOG':
                $this->savetoTable($request);
                $this->logAttendance($request);

                return $this->returnOk();
                break;
            case 'ATTPHOTO':
                //receiveOnSitePhoto($request);
                break;
            case 'OPERLOG':
                $this->savetoTable($request);
                $this->receiveOperationLog($request);
                break;
            default:
                $this->doNothing();
                break;
        }
        return $this->returnOk();
    }

    private function logAttendance(Request $request)
    {
        $serial=$request->SN;
        $data = $request->getContent();
        $arr = preg_split('/\\r\\n|\\r|,|\\n/', $data);//user id       //time      //status    //VERIFY          //WORKCODE       //RESERVED1
        foreach ($arr as $a) {
            $totalwords = strlen($a);
            if ($totalwords > 10) {    //if all the required data is inside the received format
                $d = explode("\t", $a);
                $empnum = $d[0];
                //$empnum = '4';
                $time = $d[1];
                $status_id = $d[2];
                $verify_id = $d[3];
                $data = ['emp_num' => $empnum, 'time' => $time, 'status_id' => $status_id, 'verify_id' => $verify_id,'serial'=>$serial];
                $this->saveAttendance($data);
            }
        }

    }

    private function receiveOperationLog(Request $request){
        $serial=$request->SN;
        $received=$request->getContent();
        $array = preg_split('/\\r\\n|\\r|,|\\n/', $received);
        foreach ($array as $a) {
            $totalwords = strlen($a);
            if ($totalwords > 10) {    //if all the required data is inside the received format
                $data = explode("\t", $a);
                $pos = strpos($data[0], 'FP');
                if ($pos !== false) {   //Fingerprint Log
                    $emp_num=str_replace("FP PIN=","",$data[0]);
                    $finger_no=str_replace("FID=","",$data[1]);
                    $size=str_replace("Size=","",$data[2]);
                    $valid=str_replace("Valid=","",$data[3]);
                    $finger_print=str_replace("TMP=","",$data[4]);
                    $formatted=['emp_num'=>$emp_num,'finger_no'=>$finger_no,'size'=>$size,'valid'=>$valid,'finger_print'=>$finger_print];
                    $this->fingerPrintLog($formatted);
                    return $this->returnOk();
                }
                $pos = strpos($data[0], 'USER');
                if ($pos !== false) {   //User Log

                }
            }
        }
    }
    private function fingerPrintLog($formatted){
        $emp_num=$formatted['emp_num'];
        $finger_no=$formatted['finger_no'];
        $size=$formatted['size'];
        $valid=$formatted['valid'];
        $finger_print=$formatted['finger_print'];

        $user=User::where('emp_num',$emp_num)->first();
        if ($user){
            UserFingerPrint::UpdateOrCreate(['user_id'=>$user->id,'finger_no'=>$finger_no],
                ['size'=>$size,'finger_print'=>$finger_print]
            );
            //send fingerprint to all other devices
            $devices=BiometricDevice::where('company_id',$user->company_id)->get();
            foreach ($devices as $device) {
                $this->sendUserFingerprintToDevice($user->id,$device->id);
            }
        }
    }

    private function doNothing()
    {

    }


    private function savetoTable(Request $request)
    {
        $new = new Biometric();
        $new->headers = $request->header();
        $new->url = $request->getMethod() . '- ' . $request->fullUrl();
        $new->data = $request->getContent();
        $new->save();
    }

}
