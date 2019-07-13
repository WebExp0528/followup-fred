import Kefir from 'kefir';

class ThreadDataManager {
    constructor() {
        var self = this;
        this._emitter;
        this._stream = Kefir.stream(emitter => {
            self._emitter = emitter;
            return () => null
        });
        this._stream.onAny(event => {
            console.log('Stream event:', event);
        });
        this._threadData = {}; //map from threadID to thread data
    }

    setThreadData(threadID, data) {
        console.log("setting data for ", threadID, " to: ", data)
        this._threadData[threadID] = data;
        this._emitter.emit({
            threadID,
            data
        });
    }

    getThreadData(threadID) {
        return this._threadData[threadID];
    }

    getThreadStream(threadID) {
        console.log(this._emitter)
        return this._stream.filter(event => event.threadID === threadID);
    }
}

export const threadDataManager = () => {
    return new ThreadDataManager()
}