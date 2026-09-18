export type WorkStackParamList = {
  WorkList: undefined;
  WorkDetail: { workId: number; workName: string };
  ProgressUpdate: { workId: number; workName: string; stageId?: number; stageName?: string };
};

export type MainTabParamList = {
  Dashboard: undefined;
  WorkProgress: undefined;
  Profile: undefined;
};

export type ProfileStackParamList = {
  ProfileHome: undefined;
  ChangePassword: undefined;
};
