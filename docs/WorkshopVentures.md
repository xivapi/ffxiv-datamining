# Workshop Exploratory Ventures

Both airships and submarines use essentially the same formulae, with slightly different coefficients.
There are two main segments: the duration to destination, and the duration of survey, which gives you the trip total. 
While these functions are newly discovered, they haven't changed since 4.0 at the latest.

The way venture duration functions work is somewhat awkward, but effectively it’s a 12 hour baseline with survey duration and travel duration added to it.
These are themselves based on a distance calculation relative to the map.
Survey time is static, but travel time is separate for airships and submarines.
Exploration is a placeholder, the action sheets are AirshipExploration and SubmarineExploration respectively.
Note that the survey duration field is in minutes, not seconds, hence the multiplication by 60.
Speed in this case is the speed stat of your ship.

### General functions

`var surveyTime = 60 × floor(7000 × Exploration.SurveyDuration<min> / Speed × 100)`
`var voyageDuration = 12 * 60 * 60 + voyageTime + surveyTime`

### Airships

Start node is row 127
`var voyageSpan = round(sqrt((cur.x - next.x)^2 + (cur.y - next.y)^2))`
`var voyageTime = 60 * floor(7000 * floor(855 * voyageSpan / 1000) / floor(100 * Speed))`
`var voyageDistance = floor(52 * voyageSpan / 1000)`

### Submarines

Start node is the first row of the map
`var voyageSpan = sqrt((cur.x - next.x)^2 + (cur.y - next.y)^2 + (cur.z - next.z)^2)`
`var voyageTime = 60 * floor(3990 * voyageSpan / (Speed * 100))`
`var voyageDistance = floor(0.035 * voyageSpan)`

#### Code example

```ts
export class WorkshopData {
    x: number;
    y: number;
    surveyDistance: number;
    scalar = 1;

    getTime(next: WorkshopData, speed: number): { voyageTime: number, voyageDistance: number, surveyTime: number } {
        var voyageTime = 60 * (this.voyageDistanceInter(next, speed));
        var voyageDistance = Math.trunc((this.scalar * Math.trunc(this.distance(next)) / 1000));
        var surveyTime = Math.trunc((next.surveyDistance * 7000) / (100 * speed)) * 60;
        return { voyageDistance, voyageTime, surveyTime };
    }

    distance(next: WorkshopData): number {
        throw "Abstract method triggered";
    }

    voyageDistanceInter(next: WorkshopData, speed: number): number {
        throw "Abstract method triggered";
    }
}

export class AirshipData extends WorkshopData {
    scalar = 52;
    constructor(data: any) {
        super();
        this.x = data.x;
        this.y = data.y;
        this.surveyDistance = data.surveyDistance;
    }

    distance(next: WorkshopData): number {
        return Math.round(Math.sqrt(Math.pow(this.x - next.x, 2) + Math.pow(this.y - next.y, 2)));
    }

    voyageDistanceInter(next: WorkshopData, speed: number): number {
        return Math.trunc(7000 * Math.trunc(855 * Math.trunc(this.distance(next)) / 1000) / (100 * speed))
    }
}

export class SubmarineData extends WorkshopData {
    z: number;
    scalar = 35;

    constructor(data: any) {
        super();
        this.x = data.x;
        this.y = data.y;
        this.z = data.z;
        this.surveyDistance = data.surveyDistance;
    }

    distance(next: SubmarineData): number {
        return Math.sqrt(Math.pow(this.x - next.x, 2) + Math.pow(this.y - next.y, 2) + Math.pow(this.z - next.z, 2));
    }

    voyageDistanceInter(next: SubmarineData, speed: number): number {
        return Math.trunc(3990 * this.distance(next) / (100 * speed))
    }
}
```